<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\Cash;
use App\Models\Transaction;
use DataTables;
use Illuminate\Http\Request;

class TransactionCRUDController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Transaction::with(['client', 'cash1', 'cash2', 'user']);

            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
            }
            if ($request->filled('type') && in_array($request->type, ['1', '2'])) {
                $query->where('type', $request->type);
            }
            if ($request->filled('cash_id')) {
                $query->where('cash1_id', $request->cash_id);
            }

            return Datatables::of($query->orderByDesc('id'))
                ->addIndexColumn()
                ->addColumn('client_ci', fn($r) => $r->client?->ci ?? '—')
                ->addColumn('client_name', function ($r) {
                    if (!$r->client) return '<span class="text-muted">—</span>';
                    return e($r->client->name) . ' ' . e($r->client->lastname);
                })
                ->addColumn('cash1_name', fn($r) => strtoupper($r->cash1?->getRawOriginal('name') ?? '—'))
                ->addColumn('cash2_name', fn($r) => strtoupper($r->cash2?->getRawOriginal('name') ?? '—'))
                ->addColumn('type_badge', function ($r) {
                    return $r->type_label === 'Compra'
                        ? '<span class="badge bg-soft-success text-success">Compra</span>'
                        : '<span class="badge bg-soft-warning text-warning">Venta</span>';
                })
                ->addColumn('amount1_fmt', fn($r) => number_format((float) $r->amount1, 2, '.', ','))
                ->addColumn('amount2_fmt', fn($r) => number_format((float) $r->amount2, 2, '.', ','))
                ->addColumn('date_fmt', fn($r) => $r->date?->format('d/m/Y') ?? '—')
                ->addColumn('action', function ($row) {
                    return '<a class="text-danger remove-item-btn" href="#"
                            data-id="' . e($row->id) . '"
                            data-bs-toggle="modal" data-bs-target="#deleteRecordModal">
                        <i class="ri-delete-bin-5-fill fs-16"></i>
                    </a>';
                })
                ->rawColumns(['client_name', 'type_badge', 'action'])
                ->make(true);
        }

        return view('transaction.crud', [
            'cashes' => Cash::active()->orderBy('id')->get(),
        ]);
    }

    public function edit($id)
    {
        return response()->json(Transaction::with(['client', 'cash1', 'cash2'])->findOrFail($id));
    }

    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();

        app(\App\Services\DashboardService::class)->invalidate();

        return response()->json(['success' => 'Transacción eliminada correctamente.']);
    }
}
