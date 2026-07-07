<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuotationRequest;
use App\Models\Cash;
use App\Models\Client;
use App\Models\Quotation;
use DataTables;
use Illuminate\Http\Request;

class QuotationCRUDController extends Controller
{
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
                ->addColumn('status_label', fn ($row) => $row->status === 1
                    ? '<span class="badge bg-success-subtle text-success">Vigente</span>'
                    : '<span class="badge bg-danger-subtle text-danger">Anulada</span>')
                ->addColumn('action', function ($row) {
                    return '
                        <ul class="list-inline hstack gap-2 mb-0">
                            <li class="list-inline-item edit">
                                <a href="#showModal" data-bs-toggle="modal" data-id="' . e($row->id) . '"
                                   class="text-primary d-inline-block edit-item-btn">
                                    <i class="ri-pencil-fill fs-16"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a class="text-danger d-inline-block remove-item-btn" data-id="' . e($row->id) . '"
                                   data-bs-toggle="modal" href="#deleteRecordModal">
                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                </a>
                            </li>
                        </ul>';
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
            'status'      => $data['status'] ?? 1,
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

    public function edit($id)
    {
        return response()->json(Quotation::findOrFail($id));
    }

    public function destroy($id)
    {
        Quotation::findOrFail($id)->delete();

        return response()->json(['success' => 'Cotización eliminada correctamente.']);
    }
}
