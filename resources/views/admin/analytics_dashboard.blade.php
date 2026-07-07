@extends('layout.masteradmin')

@section('body')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Page title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Dashboard Analítico</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Admin</a></li>
                                <li class="breadcrumb-item active">Analytics</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loading state --}}
            <div id="analytics-loader" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="text-muted mt-2">Cargando métricas...</p>
            </div>

            {{-- KPI Cards --}}
            <div class="row" id="kpi-row" style="display:none;"></div>

            {{-- Charts --}}
            <div class="row" id="charts-row" style="display:none;">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h5 class="card-title mb-0 flex-grow-1">Transacciones por divisa</h5>
                            <span class="badge bg-soft-primary text-primary">Últimos 7 días</span>
                        </div>
                        <div class="card-body">
                            <div id="chart-currency" style="min-height:300px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h5 class="card-title mb-0 flex-grow-1">Volumen compra (BOB)</h5>
                            <span class="badge bg-soft-success text-success">Últimos 30 días</span>
                        </div>
                        <div class="card-body">
                            <div id="chart-volume" style="min-height:300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top clients --}}
            <div class="row" id="clients-row" style="display:none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">Top 5 clientes</h5>
                            <span class="badge bg-soft-warning text-warning fs-11" id="top-clients-period">mes actual</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width:40px;">#</th>
                                            <th>CI</th>
                                            <th>Nombre</th>
                                            <th class="text-center">Transacciones</th>
                                            <th class="text-end">Volumen BOB</th>
                                        </tr>
                                    </thead>
                                    <tbody id="top-clients-body">
                                        <tr><td colspan="5" class="text-center text-muted py-4">Sin datos este mes</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Error state --}}
            <div id="analytics-error" class="alert alert-warning d-none" role="alert">
                <i class="ri-error-warning-line me-2"></i>
                No se pudieron cargar las métricas. <a href="#" onclick="loadAnalytics();return false;">Reintentar</a>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
(function () {
    'use strict';

    const API_URL = '{{ route("admin.analytics.api") }}';

    function fmt(n, dec) {
        return Number(n ?? 0).toLocaleString('es-BO', {
            minimumFractionDigits: dec ?? 0,
            maximumFractionDigits: dec ?? 0,
        });
    }

    function esc(str) {
        return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // ----------------------------------------------------------------
    // KPI cards
    // ----------------------------------------------------------------
    function kpiCard(icon, color, label, value, sub) {
        return `
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">${label}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold mb-2">${value}</h4>
                            <p class="text-muted mb-0 fs-12">${sub}</p>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-${color} rounded fs-3">
                                <i class="${icon} text-${color}"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    // ----------------------------------------------------------------
    // Charts
    // ----------------------------------------------------------------
    let chartCurrency = null;
    let chartVolume   = null;

    function renderCharts(data) {
        const byCurrency = data.chart_by_currency;
        const byDay      = data.chart_volume;

        if (chartCurrency) { chartCurrency.destroy(); chartCurrency = null; }
        if (chartVolume)   { chartVolume.destroy();   chartVolume   = null; }

        if (byCurrency.labels.length) {
            chartCurrency = new ApexCharts(document.querySelector('#chart-currency'), {
                chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [{ name: 'Transacciones', data: byCurrency.data }],
                xaxis: { categories: byCurrency.labels },
                colors: ['#405189', '#0ab39c', '#f7b84b', '#f06548', '#299cdb', '#45cb85'],
                plotOptions: { bar: { borderRadius: 4, distributed: true } },
                legend: { show: false },
                dataLabels: { enabled: true },
                grid: { yaxis: { lines: { show: false } } },
                tooltip: { y: { formatter: v => v + ' operaciones' } },
            });
            chartCurrency.render();
        } else {
            document.querySelector('#chart-currency').innerHTML =
                '<p class="text-center text-muted py-5">Sin transacciones en los últimos 7 días</p>';
        }

        if (byDay.labels.length) {
            chartVolume = new ApexCharts(document.querySelector('#chart-volume'), {
                chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [{ name: 'Volumen (BOB)', data: byDay.data }],
                xaxis: { categories: byDay.labels, tickAmount: 6, labels: { rotate: -30 } },
                colors: ['#0ab39c'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.35, opacityTo: 0.05 } },
                yaxis: { labels: { formatter: v => 'Bs ' + fmt(v, 0) } },
                tooltip: { y: { formatter: v => 'Bs ' + fmt(v, 2) } },
                grid: { strokeDashArray: 3 },
            });
            chartVolume.render();
        } else {
            document.querySelector('#chart-volume').innerHTML =
                '<p class="text-center text-muted py-5">Sin volumen en los últimos 30 días</p>';
        }
    }

    // ----------------------------------------------------------------
    // Top clients table
    // ----------------------------------------------------------------
    function renderTopClients(clients) {
        const tbody = document.getElementById('top-clients-body');
        if (!clients.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Sin clientes este mes</td></tr>';
            return;
        }
        tbody.innerHTML = clients.map((c, i) => `
            <tr>
                <td class="text-center fw-semibold">${i + 1}</td>
                <td><code>${esc(c.ci)}</code></td>
                <td>${esc(c.name)} ${esc(c.lastname)}</td>
                <td class="text-center">
                    <span class="badge bg-soft-primary text-primary">${c.tx_count}</span>
                </td>
                <td class="text-end fw-semibold">Bs ${fmt(c.volume, 2)}</td>
            </tr>`).join('');
    }

    // ----------------------------------------------------------------
    // Main load
    // ----------------------------------------------------------------
    window.loadAnalytics = async function () {
        document.getElementById('analytics-loader').style.display = '';
        document.getElementById('analytics-error').classList.add('d-none');
        ['kpi-row', 'charts-row', 'clients-row'].forEach(id => {
            document.getElementById(id).style.display = 'none';
        });

        try {
            const res  = await fetch(API_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();
            const k    = json.kpis;

            const month = new Date().toLocaleString('es-BO', { month: 'long', year: 'numeric' });

            // KPI row
            document.getElementById('kpi-row').innerHTML = [
                kpiCard('ri-exchange-line',           'primary', 'Operaciones hoy',       fmt(k.today_tx),                         'del día'),
                kpiCard('ri-money-dollar-circle-line','success', 'Volumen BOB (mes)',      'Bs ' + fmt(k.month_volume_bob, 2),      month),
                kpiCard('ri-user-add-line',           'info',    'Clientes nuevos (mes)',  fmt(k.new_clients),                      month),
                kpiCard('ri-line-chart-line',         'warning', 'Divisa más operada',     String(k.top_currency ?? '—').toUpperCase(), month),
            ].join('');

            document.getElementById('top-clients-period').textContent = month;

            ['kpi-row', 'charts-row', 'clients-row'].forEach(id => {
                document.getElementById(id).style.display = '';
            });

            renderCharts(json);
            renderTopClients(json.top_clients);

        } catch (err) {
            console.error('Analytics load error', err);
            document.getElementById('analytics-error').classList.remove('d-none');
        } finally {
            document.getElementById('analytics-loader').style.display = 'none';
        }
    };

    document.addEventListener('DOMContentLoaded', window.loadAnalytics);
})();
</script>
@endsection
