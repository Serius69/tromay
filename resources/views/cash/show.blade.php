@extends('layout.master')
@section('title', e($cash->name) . ' — Tasas de Cambio')
@section('description', e('Tipo de cambio de ' . strtoupper($cash->name) . ' en Bolivia hoy: compra Bs ' . number_format($cash->buy, 2) . ' y venta Bs ' . number_format($cash->sell, 2) . ' en Tromay, la casa de cambio física #1 de La Paz. Tasas en vivo desde forex.'))

@section('body')

{{-- SEO — FinancialProduct + Offer (compra/venta) de esta divisa (complementa el JSON-LD global de master) --}}
@php
    $ldCode = strtolower($cash->name);
    $ldLabels = [
        'usd' => 'Dólar Estadounidense', 'eur' => 'Euro', 'brl' => 'Real Brasileño',
        'ars' => 'Peso Argentino', 'pen' => 'Sol Peruano', 'clp' => 'Peso Chileno',
    ];
    $ldLabel = $ldLabels[$ldCode] ?? $cash->name;
    $ldUp    = strtoupper($ldCode);
    $cashLd = [
        '@context' => 'https://schema.org',
        '@type'    => 'FinancialProduct',
        '@id'      => route('dinero.show', $cash->id) . '#producto',
        'name'     => 'Tipo de cambio ' . $ldUp . ' — ' . $ldLabel . ' en Bolivia',
        'category' => 'CurrencyExchange',
        'url'      => route('dinero.show', $cash->id),
        'provider' => ['@type' => 'FinancialService', 'name' => 'Tromay Casa de Cambio', 'url' => rtrim(url('/'), '/')],
        'offers'   => [
            '@type'         => 'Offer',
            'itemOffered'   => ['@type' => 'Service', 'name' => 'Compra y venta de ' . $ldLabel . ' (' . $ldUp . ')'],
            'priceCurrency' => 'BOB',
            'priceSpecification' => [
                ['@type' => 'UnitPriceSpecification', 'name' => 'Compra ' . $ldUp, 'price' => number_format((float) $cash->buy, 6, '.', ''),  'priceCurrency' => 'BOB'],
                ['@type' => 'UnitPriceSpecification', 'name' => 'Venta '  . $ldUp, 'price' => number_format((float) $cash->sell, 6, '.', ''), 'priceCurrency' => 'BOB'],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($cashLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

{{-- Page header --}}
<div class="kap-detail-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="kap-detail-label">Detalle de divisa</div>
                <h1 class="kap-detail-h1">{{ e($cash->name) }}</h1>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="kap-breadcrumb">
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('quote') }}">Cotizaciones</a></li>
                    <li class="active">{{ e($cash->name) }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section style="padding:70px 0;background:var(--kap-black);">
    <div class="container">
        <div class="row g-4">

            {{-- Main rate card --}}
            <div class="col-lg-4 col-md-6">
                <div class="kap-sim-card h-100">
                    <div class="text-center mb-24" style="padding-bottom:20px;border-bottom:1px solid var(--kap-border);">
                        @php
                        $showFlagMap=['usd'=>'estados unidos.png','eur'=>'union europea.png','clp'=>'chile.png','pen'=>'peru.png','brl'=>'brazil.png','ars'=>'argentina.png'];
                        $showFlag = $showFlagMap[strtolower($cash->name)] ?? null;
                        @endphp
                        @if($showFlag)
                        <img src="{{ url('assets/images/' . $showFlag) }}"
                             alt="{{ e($cash->name) }}"
                             class="kap-currency-flag-lg">
                        @else
                        <div class="kap-currency-flag-lg--placeholder">💱</div>
                        @endif
                        <h3 style="font-size:22px!important;margin-bottom:4px!important;">{{ e($cash->name) }}</h3>
                        <span class="kap-live-badge">En vivo</span>
                        <span data-kap-timestamp class="kap-timestamp">Actualizado ahora mismo</span>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-6">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Compra</div>
                                <div class="kap-rate-box__value kap-rate-box__value--green" data-kap-buy>
                                    {{ number_format($cash->buy, 4) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Venta</div>
                                <div class="kap-rate-box__value kap-rate-box__value--gold" data-kap-sell>
                                    {{ number_format($cash->sell, 4) }}
                                </div>
                            </div>
                        </div>
                        @if($cash->oficial)
                        <div class="col-12">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Tasa Oficial</div>
                                <div class="kap-rate-box__value kap-rate-box__value--muted">
                                    {{ number_format($cash->oficial, 4) }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('quote') }}" class="kap-sim-btn d-block text-center text-decoration-none mt-4">
                        Simular operación
                    </a>
                    <a href="{{ route('seo.convertidor', ['par' => strtolower($cash->name).'-bob']) }}"
                       class="read-more mt-3 d-inline-flex" style="font-size:13px;">
                        Convertidor {{ strtoupper(substr($cash->name,0,3)) }} / BOB <i class="flaticon-right-arrow"></i>
                    </a>
                </div>
            </div>

            {{-- Mini simulator --}}
            <div class="col-lg-4 col-md-6">
                <div class="kap-sim-card h-100">
                    <h3 class="kap-card-title">Calculadora rápida</h3>
                    <p class="kap-card-subtitle">Estimá el resultado de tu operación</p>

                    <form id="kap-simulator" novalidate>
                        @csrf
                        <div class="kap-tabs" style="margin-bottom:20px;">
                            <button type="button" class="kap-tab-btn active" data-type="buy">Compro</button>
                            <button type="button" class="kap-tab-btn" data-type="sell">Vendo</button>
                        </div>

                        <input type="hidden" id="sim-currency" value="{{ $cash->id }}">

                        <div class="kap-form-group">
                            <label for="sim-amount" class="kap-label">Monto (BOB)</label>
                            <input type="number" id="sim-amount" class="kap-input"
                                   placeholder="Ej: 500" min="1" step="0.01" autocomplete="off">
                        </div>

                        <div id="sim-result" style="display:none;"></div>

                        {{-- Embed rates for JS --}}
                        <script>
                            window.__kapRates = window.__kapRates || [];
                            window.__kapRates.push({
                                id:   {{ $cash->id }},
                                name: @json($cash->name),
                                buy:  {{ $cash->buy }},
                                sell: {{ $cash->sell }},
                            });
                        </script>
                    </form>
                </div>
            </div>

            {{-- Other currencies --}}
            <div class="col-lg-4">
                <div class="kap-sim-card h-100">
                    <h3 class="kap-card-title" style="margin-bottom:16px!important;">Otras divisas</h3>
                    @php $otherFlagMap=['usd'=>'estados unidos.png','eur'=>'union europea.png','clp'=>'chile.png','pen'=>'peru.png','brl'=>'brazil.png','ars'=>'argentina.png']; @endphp
                    @foreach($cashes->where('id', '!=', $cash->id)->take(6) as $other)
                    @php $otherFlag=$otherFlagMap[strtolower($other->name)]??null; @endphp
                    <div class="kap-rate-row" data-kap-id="{{ $other->id }}">
                        <div class="kap-rc">
                            <div class="kap-rc-flag">
                                @if($otherFlag)
                                <img src="{{ url('assets/images/' . $otherFlag) }}"
                                     alt="{{ e($other->name) }}"
                                     style="width:100%;height:100%;object-fit:cover;border-radius:5px;">
                                @else
                                <span>💱</span>
                                @endif
                            </div>
                            <span class="kap-rc-code">{{ e($other->name) }}</span>
                        </div>
                        <div class="kap-rv">
                            <div class="kap-rv-item">
                                <div class="lbl">C</div>
                                <div class="val buy" data-kap-buy>{{ number_format($other->buy, 4) }}</div>
                            </div>
                            <div class="kap-rv-item">
                                <div class="lbl">V</div>
                                <div class="val sell" data-kap-sell>{{ number_format($other->sell, 4) }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <a href="{{ route('quote') }}" class="read-more mt-3 d-inline-flex" style="font-size:13px;">
                        Ver todas las divisas <i class="flaticon-right-arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Rate history section --}}
<section style="padding:0 0 70px;background:var(--kap-black);">
    <div class="container">
        <div class="kap-sim-card">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <div>
                    <h3 style="font-size:18px!important;margin-bottom:4px!important;">Historial de tasas — {{ e($cash->name) }}</h3>
                    <p style="font-size:13px;color:var(--kap-text-muted);margin:0;">Últimas 60 variaciones registradas</p>
                </div>
                <span class="kap-live-badge">Automático</span>
            </div>
            <div id="rate-history-chart" style="min-height:280px;"></div>
            <p id="rate-history-empty" class="text-center text-muted mt-4" style="display:none;font-size:13px;">
                Aún no hay historial de tasas registrado para esta divisa.
            </p>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
(function () {
    var cashId = {{ $cash->id }};
    var apiUrl = '/api/rates/' + cashId + '/history';

    fetch(apiUrl)
        .then(function (r) { return r.json(); })
        .then(function (json) {
            var history = (json.data || json).slice().reverse();
            if (!Array.isArray(history) || history.length === 0) {
                document.getElementById('rate-history-empty').style.display = '';
                return;
            }

            var labels = history.map(function (h) {
                return new Date(h.created_at).toLocaleDateString('es-BO', { month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' });
            });
            var buyData  = history.map(function (h) { return parseFloat(h.buy);  });
            var sellData = history.map(function (h) { return parseFloat(h.sell); });

            var isDark = document.documentElement.getAttribute('data-theme') !== 'light';
            var textColor   = isDark ? '#94a3b8' : '#64748b';
            var borderColor = isDark ? '#1e293b' : '#e2e8f0';

            var options = {
                chart: {
                    type: 'area',
                    height: 280,
                    background: 'transparent',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    animations: { enabled: true, easing: 'easeinout', speed: 600 }
                },
                theme: { mode: isDark ? 'dark' : 'light' },
                series: [
                    { name: 'Compra', data: buyData  },
                    { name: 'Venta',  data: sellData }
                ],
                xaxis: {
                    categories: labels,
                    labels: {
                        rotate: -30,
                        style: { fontSize: '10px', colors: textColor }
                    },
                    tickAmount: Math.min(10, labels.length)
                },
                yaxis: {
                    labels: {
                        formatter: function (v) { return v.toFixed(4); },
                        style: { colors: textColor }
                    }
                },
                colors: ['#00d97e', '#f6c90e'],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 90, 100] }
                },
                stroke: { curve: 'smooth', width: 2 },
                dataLabels: { enabled: false },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: { formatter: function (v) { return 'BOB ' + v.toFixed(4); } }
                },
                grid: { borderColor: borderColor },
                legend: { labels: { colors: textColor } }
            };

            var chart = new ApexCharts(document.getElementById('rate-history-chart'), options);
            chart.render();
        })
        .catch(function () {
            document.getElementById('rate-history-empty').style.display = '';
        });
})();
</script>
@endsection
