<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Crud\Concerns\BuildsCrudActions;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\Cash;
use App\Models\Client;
use App\Models\Quotation;
use App\Services\TransactionService;
use DataTables;
use Illuminate\Http\Request;

class QuotationCRUDController extends Controller
{
    use BuildsCrudActions;

    public function __construct(private TransactionService $txService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Quotation::query()
                ->with(['cash:id,name', 'client:id,name,lastname'])
                ->select(['id', 'client_id', 'cash_id', 'type', 'amount', 'rate', 'total', 'valid_until', 'status', 'created_at']);

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('currency', fn ($row) => $row->cash ? strtoupper($row->cash->getRawOriginal('name')) : '—')
                ->addColumn('client_name', fn ($row) => $row->client ? "{$row->client->name} {$row->client->lastname}" : 'Sin cliente')
                ->editColumn('type', fn ($row) => $row->type === 'buy' ? 'Compra' : 'Venta')
                ->editColumn('valid_until', fn ($row) => optional($row->valid_until)->format('d/m/Y') ?? '—')
                ->addColumn('status_label', fn ($row) => match ($row->status) {
                    Quotation::STATUS_VIGENTE    => '<span class="badge bg-success-subtle text-success">Vigente</span>',
                    Quotation::STATUS_CONVERTIDA => '<span class="badge bg-info-subtle text-info">Convertida</span>',
                    default                      => '<span class="badge bg-danger-subtle text-danger">Anulada</span>',
                })
                ->addColumn('action', function ($row) {
                    $convert = $row->isConvertible()
                        ? '<li class="list-inline-item">
                                <a class="text-success d-inline-block convert-item-btn" data-id="' . e($row->id) . '"
                                   data-bs-toggle="modal" href="#convertRecordModal" title="Convertir en transacción">
                                    <i class="ri-exchange-dollar-fill fs-16"></i>
                                </a>
                            </li>'
                        : '';

                    // Una cotización convertida es el registro de auditoría de la
                    // tasa pactada: no se edita ni se elimina.
                    $mutable = $row->status !== Quotation::STATUS_CONVERTIDA;

                    return $this->crudActions($row->id, $convert, editable: $mutable, deletable: $mutable);
                })
                ->rawColumns(['action', 'status_label'])
                ->make(true);
        }

        return view('quotation.crud', [
            'cashes'  => Cash::active()->orderBy('name')->get(['id', 'name', 'buy', 'sell']),
            'clients' => Client::where('status', 1)->orderBy('name')->get(['id', 'ci', 'name', 'lastname']),
        ]);
    }

    public function store(StoreQuotationRequest $request)
    {
        $data = $request->validated();

        if ($request->filled('id')) {
            $existing = Quotation::findOrFail($request->input('id'));
            abort_if($existing->status === Quotation::STATUS_CONVERTIDA, 422, 'Una cotización convertida no puede modificarse.');
        }

        // La tasa y el total se calculan en el servidor a partir de la divisa;
        // nunca se confían valores enviados por el cliente.
        $cash = Cash::findOrFail($data['cash_id']);
        $rate = $data['type'] === 'buy' ? (float) $cash->buy : (float) $cash->sell;

        $payload = [
            'client_id'   => $data['client_id'] ?? null,
            'cash_id'     => $data['cash_id'],
            'type'        => $data['type'],
            'amount'      => (float) $data['amount'],
            'rate'        => $rate,
            'total'       => round((float) $data['amount'] * $rate, 4),
            'valid_until' => $data['valid_until'] ?? today()->addDay()->toDateString(),
            'notes'       => $data['notes'] ?? null,
            'status'      => $data['status'] ?? Quotation::STATUS_VIGENTE,
        ];

        $quotation = Quotation::updateOrCreate(
            ['id' => $request->input('id')],
            $payload,
        );

        return response()->json([
            'success' => 'Cotización guardada correctamente.',
            'data'    => $quotation,
        ]);
    }

    /**
     * Convierte una cotización vigente en una transacción real (1 clic).
     * Validaciones y bloqueo anti doble-conversión en TransactionService.
     */
    public function convert(Quotation $quotation)
    {
        $transaction = $this->txService->registerFromQuotation($quotation);

        return response()->json([
            'success'     => 'Cotización convertida en transacción correctamente.',
            'transaction' => $transaction->load(['client', 'cash1', 'cash2']),
        ]);
    }

    public function edit($id)
    {
        return response()->json(Quotation::findOrFail($id));
    }

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        abort_if($quotation->status === Quotation::STATUS_CONVERTIDA, 422, 'Una cotización convertida no puede eliminarse: es el respaldo de la tasa pactada.');

        $quotation->delete();

        return response()->json(['success' => 'Cotización eliminada correctamente.']);
    }
}
