<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * GET /api/transactions/datatable
 *
 * Implementación manual del protocolo DataTables server-side (sin Yajra)
 * optimizada para 100k+ registros con Query Builder + índices compuestos.
 *
 * Parámetros esperados:
 *   draw, start, length, search[value],
 *   order[0][column], order[0][dir]
 */
class TransactionDatatableController extends Controller
{
    // Columnas disponibles para ordenamiento (índice DataTables → columna SQL)
    private const ORDERABLE_COLUMNS = [
        0 => 'transactions.id',
        1 => 'transactions.date',
        2 => 'clients.ci',
        3 => 'transactions.type',
        4 => 'transactions.amount1',
        5 => 'transactions.amount2',
        6 => 'transactions.created_at',
    ];

    public function __invoke(Request $request): JsonResponse
    {
        $draw   = (int) $request->input('draw', 1);
        $start  = max(0, (int) $request->input('start', 0));
        $length = min(500, max(1, (int) $request->input('length', 25)));
        $search = trim((string) $request->input('search.value', ''));

        $colIdx = (int) $request->input('order.0.column', 1);
        $dir    = strtolower($request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $orderCol = self::ORDERABLE_COLUMNS[$colIdx] ?? 'transactions.date';

        // ── Base query con joins ─────────────────────────────────────────────
        $base = DB::table('transactions')
            ->leftJoin('clients', 'transactions.client_id', '=', 'clients.id')
            ->leftJoin('cashes as c1', 'transactions.cash1_id', '=', 'c1.id')
            ->leftJoin('cashes as c2', 'transactions.cash2_id', '=', 'c2.id')
            ->select([
                'transactions.id',
                'transactions.type',
                'transactions.amount1',
                'transactions.amount2',
                'transactions.date',
                'transactions.created_at',
                'clients.ci      as client_ci',
                'clients.name    as client_name',
                'clients.lastname as client_lastname',
                'c1.name         as cash1_name',
                'c2.name         as cash2_name',
            ]);

        // ── Total sin filtro (usa índice primario — rápido) ──────────────────
        $recordsTotal = DB::table('transactions')->count();

        // ── Filtro de búsqueda ───────────────────────────────────────────────
        if ($search !== '') {
            $like = '%' . addcslashes($search, '%_\\') . '%';
            $base->where(function ($q) use ($like) {
                $q->whereRaw('clients.ci LIKE ?',           [$like])
                  ->orWhereRaw('clients.name LIKE ?',       [$like])
                  ->orWhereRaw('clients.lastname LIKE ?',   [$like])
                  ->orWhereRaw('c1.name LIKE ?',            [$like])
                  ->orWhereRaw('c2.name LIKE ?',            [$like]);
            });
        }

        $recordsFiltered = (clone $base)->count();

        // ── Datos paginados ──────────────────────────────────────────────────
        $rows = (clone $base)
            ->orderBy($orderCol, $dir)
            ->offset($start)
            ->limit($length)
            ->get();

        $data = $rows->map(fn($r) => [
            'id'           => $r->id,
            'date'         => $r->date ? date('d/m/Y', strtotime($r->date)) : '—',
            'client_ci'    => $r->client_ci    ?? '—',
            'client_name'  => trim(($r->client_name ?? '') . ' ' . ($r->client_lastname ?? '')) ?: '—',
            'cash1_name'   => strtoupper($r->cash1_name ?? '—'),
            'cash2_name'   => strtoupper($r->cash2_name ?? '—'),
            'type'         => $r->type,
            'type_label'   => $r->type === 1 ? 'Compra' : 'Venta',
            'amount1'      => number_format((float) $r->amount1, 2, '.', ','),
            'amount2'      => number_format((float) $r->amount2, 2, '.', ','),
        ])->values()->all();

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ]);
    }
}
