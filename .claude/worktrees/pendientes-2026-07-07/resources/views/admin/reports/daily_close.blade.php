@extends('layout.masteradmin')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('body')
<style>
@media print {
    .no-print, #page-topbar, .app-menu, .vertical-overlay,
    .page-title-box .page-title-right, .btn-print, .customizer-setting { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .card { border: none !important; box-shadow: none !important; }
    .page-content { padding: 0 !important; }
    body { font-size: 12px; }
}
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Cierre diario</h4>
                        <div class="page-title-right d-flex gap-2 align-items-center no-print">
                            <form method="GET" action="{{ route('admin.reports.daily-close') }}" class="d-flex gap-2">
                                <input type="date" name="date" class="form-control form-control-sm"
                                    value="{{ $date }}" max="{{ date('Y-m-d') }}">
                                <button type="submit" class="btn btn-primary btn-sm">Consultar</button>
                            </form>
                            <a href="{{ route('admin.reports.daily-close', ['date' => $date, 'export' => 'csv']) }}"
                               class="btn btn-outline-success btn-sm">
                                <i class="ri-file-excel-2-line me-1"></i>Excel / CSV
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm btn-print">
                                <i class="ri-printer-line me-1"></i>Imprimir / PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Print header (shown only when printing) --}}
            <div class="d-none d-print-block mb-3">
                <h3>Tromay Casa de Cambio — Cierre Diario</h3>
                <p>Fecha: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} | Generado: {{ now()->format('d/m/Y H:i') }}</p>
            </div>

            {{-- KPI summary --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <div class="fs-22 fw-bold text-primary">{{ $total_count }}</div>
                            <div class="text-muted fs-13">Total operaciones</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <div class="fs-22 fw-bold text-success">{{ $buy_count }}</div>
                            <div class="text-muted fs-13">Compras (clientes venden)</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <div class="fs-22 fw-bold text-warning">{{ $sell_count }}</div>
                            <div class="text-muted fs-13">Ventas (clientes compran)</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <div class="fs-22 fw-bold text-info">
                                Bs {{ number_format($buy_volume + $sell_volume, 2) }}
                            </div>
                            <div class="text-muted fs-13">Volumen total (BOB)</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transactions table --}}
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">
                        Transacciones del {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    </h5>
                    <span class="badge bg-primary no-print">{{ $total_count }} registros</span>
                </div>
                <div class="card-body p-0">
                    @if($transactions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="ri-inbox-line fs-36 d-block mb-2"></i>
                            No hay transacciones para esta fecha.
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Hora</th>
                                    <th>CI Cliente</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Div. origen</th>
                                    <th>Div. destino</th>
                                    <th class="text-end">Monto 1</th>
                                    <th class="text-end">Monto 2</th>
                                    <th class="no-print">Cajero</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $i => $tx)
                                <tr>
                                    <td class="text-muted fs-12">{{ $i + 1 }}</td>
                                    <td class="fs-12">{{ $tx->date?->format('H:i') ?? '—' }}</td>
                                    <td><code>{{ $tx->client?->ci ?? '—' }}</code></td>
                                    <td>{{ $tx->client?->name }} {{ $tx->client?->lastname }}</td>
                                    <td>
                                        @if($tx->type_label === 'Compra')
                                            <span class="badge bg-soft-success text-success">Compra</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning">Venta</span>
                                        @endif
                                    </td>
                                    <td>{{ strtoupper($tx->cash1?->getRawOriginal('name') ?? '—') }}</td>
                                    <td>{{ strtoupper($tx->cash2?->getRawOriginal('name') ?? '—') }}</td>
                                    <td class="text-end">{{ number_format($tx->amount1, 2) }}</td>
                                    <td class="text-end fw-semibold">{{ number_format($tx->amount2, 2) }}</td>
                                    <td class="no-print text-muted fs-12">{{ $tx->user?->name ?? '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <td colspan="7" class="fw-semibold text-end">Totales</td>
                                    <td class="text-end fw-semibold">{{ number_format($transactions->sum('amount1'), 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($transactions->sum('amount2'), 2) }}</td>
                                    <td class="no-print"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
