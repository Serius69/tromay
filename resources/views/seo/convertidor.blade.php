@extends('layout.master')

@php
    $ccode  = strtolower($code);
    $cLabel = $meta[$ccode]['label']   ?? $cash->name;
    $cShort = $meta[$ccode]['short']   ?? strtolower($cash->name);
    $cCty   = $meta[$ccode]['country'] ?? '';
    $cFlag  = $meta[$ccode]['flag']    ?? null;
    $up     = strtoupper($ccode);

    $buy2  = number_format($cash->buy, 2);
    $sell2 = number_format($cash->sell, 2);
    $buy4  = number_format($cash->buy, 4);
    $sell4 = number_format($cash->sell, 4);

    $seoDesc = "Convertidor {$up} a bolivianos (BOB) en vivo. 1 {$up} = Bs {$sell2} (venta) y Bs {$buy2} (compra) "
        . "en Tromay, la casa de cambio física #1 de La Paz. Calculá tu cambio de {$cShort} al instante.";

    $waConv = 'https://api.whatsapp.com/send?phone=59164082967&text='
        . rawurlencode("Hola Tromay, quiero cambiar {$cShort} ({$up}). ¿Me confirman la tasa de hoy?");
@endphp

@section('title', 'Convertidor ' . $up . ' a Bolivianos (BOB) · Tasa en Vivo')
@section('description', $seoDesc)

@section('body')

{{-- ================================================================
     JSON-LD — FinancialProduct + BreadcrumbList + FAQPage
================================================================ --}}
@php
    $seoBase = rtrim(url('/'), '/');
    $convUrl = $seoBase . '/convertidor/' . $ccode . '-bob';
    $seoLd = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'    => 'FinancialProduct',
                '@id'      => $convUrl . '#producto',
                'name'     => "Convertidor {$up} a BOB — {$cLabel}",
                'category' => 'CurrencyExchange',
                'url'      => $convUrl,
                'provider' => ['@type' => 'FinancialService', 'name' => 'Tromay Casa de Cambio', 'url' => $seoBase],
                'offers'   => [
                    '@type'         => 'Offer',
                    'itemOffered'   => ['@type' => 'Service', 'name' => "Compra y venta de {$cLabel} ({$up})"],
                    'priceCurrency' => 'BOB',
                    'priceSpecification' => [
                        ['@type' => 'UnitPriceSpecification', 'name' => "Compra {$up}", 'price' => number_format((float) $cash->buy, 6, '.', ''),  'priceCurrency' => 'BOB'],
                        ['@type' => 'UnitPriceSpecification', 'name' => "Venta {$up}",  'price' => number_format((float) $cash->sell, 6, '.', ''), 'priceCurrency' => 'BOB'],
                    ],
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => $seoBase],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => "Convertidor {$up} a BOB", 'item' => $convUrl],
                ],
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name'  => "¿A cuánto está 1 {$up} en bolivianos hoy?",
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => "Hoy 1 {$up} equivale a Bs {$sell2} si comprás la divisa (venta de Tromay) y a Bs {$buy2} si la vendés (compra de Tromay). Son tasas referenciales en vivo; la cotización final se confirma en sucursal."],
                    ],
                    [
                        '@type' => 'Question',
                        'name'  => "¿Cómo convierto {$cShort} a bolivianos?",
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => "Ingresá el monto en el convertidor de esta página y elegí si comprás o vendés {$cShort}. El cálculo usa la tasa en vivo de Tromay y te arma un mensaje de WhatsApp para confirmar la operación en la sucursal de Villa Fátima, La Paz."],
                    ],
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($seoLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

<style>
    .seo-prose{max-width:820px;margin:0 auto;}
    .seo-prose h2{font-size:26px;margin:0 0 14px;color:var(--kap-text);}
    .seo-prose h3{font-size:19px;margin:26px 0 8px;color:var(--kap-text);}
    .seo-prose p{color:var(--kap-text-muted);line-height:1.75;margin:0 0 14px;font-size:15.5px;}
    .seo-prose strong{color:var(--kap-text);}
</style>

{{-- Page header --}}
<div class="kap-detail-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="kap-detail-label">Convertidor de divisas</div>
                <h1 class="kap-detail-h1">Convertidor {{ $up }} a Bolivianos</h1>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="kap-breadcrumb">
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('dolar-hoy') }}">Tipo de cambio</a></li>
                    <li class="active">{{ $up }} / BOB</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section style="padding:60px 0;background:var(--kap-black);">
    <div class="container">
        <div class="row g-4 align-items-stretch">

            {{-- Live rate + intro --}}
            <div class="col-lg-6">
                <div class="kap-sim-card h-100" data-kap-id="{{ $cash->id }}">
                    <div class="d-flex align-items-center gap-3 mb-4" style="padding-bottom:18px;border-bottom:1px solid var(--kap-border);">
                        @if($cFlag)
                        <img src="{{ url('assets/images/' . $cFlag) }}" alt="{{ e($cLabel) }}"
                             style="width:54px;height:54px;border-radius:8px;object-fit:cover;">
                        @else
                        <div class="kap-currency-flag-lg--placeholder">💱</div>
                        @endif
                        <div>
                            <h2 style="font-size:22px!important;margin:0 0 2px!important;">{{ $cLabel }} ({{ $up }})</h2>
                            {{-- Badge honesto según rate_source (ver seo/dolar-hoy). --}}
                            @if(($cash->rate_source ?? null) === 'forex')
                            <span class="kap-live-badge">En vivo</span>
                            <span data-kap-timestamp class="kap-timestamp">Actualizado ahora mismo</span>
                            @elseif(($cash->rate_source ?? null) === 'cache')
                            <span class="kap-live-badge kap-live-badge--cache">Caché</span>
                            <span class="kap-timestamp">Última tasa real conocida</span>
                            @else
                            <span class="kap-live-badge kap-live-badge--cache">Referencial</span>
                            <span class="kap-timestamp">Confirmá la tasa en sucursal</span>
                            @endif
                        </div>
                    </div>

                    <p style="color:var(--kap-text-muted);font-size:15px;margin:0 0 18px;line-height:1.7;">
                        Hoy <strong style="color:var(--kap-text);">1 {{ $up }}</strong> equivale a
                        <strong style="color:var(--kap-gold);">Bs {{ $sell2 }}</strong> si comprás la divisa, y a
                        <strong style="color:var(--kap-green);">Bs {{ $buy2 }}</strong> si la vendés.
                    </p>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Compra (te compramos {{ $up }})</div>
                                <div class="kap-rate-box__value kap-rate-box__value--green" data-kap-buy>{{ $buy4 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Venta (te vendemos {{ $up }})</div>
                                <div class="kap-rate-box__value kap-rate-box__value--gold" data-kap-sell>{{ $sell4 }}</div>
                            </div>
                        </div>
                        @if($cash->oficial > 0)
                        <div class="col-12">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Tasa oficial de referencia (BCB)</div>
                                <div class="kap-rate-box__value kap-rate-box__value--muted">{{ number_format($cash->oficial, 4) }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <p style="font-size:11px;color:var(--kap-text-muted);text-align:center;margin:16px 0 0;line-height:1.5;">
                        ⚠ Tasas referenciales y estimadas. La cotización real se confirma en sucursal.
                    </p>
                </div>
            </div>

            {{-- Simulator (reusa el motor Kapitalya.calculate) --}}
            <div class="col-lg-6">
                <div class="kap-sim-card h-100">
                    <h3>Convertidor {{ $up }} / BOB</h3>
                    <p>Resultado <strong>estimado y referencial</strong> — confirmá en sucursal</p>

                    <form id="kap-simulator" novalidate>
                        @csrf

                        <div class="kap-tabs">
                            <button type="button" class="kap-tab-btn {{ $direction === 'buy' ? 'active' : '' }}" data-type="buy">
                                Compro {{ $up }}
                            </button>
                            <button type="button" class="kap-tab-btn {{ $direction === 'sell' ? 'active' : '' }}" data-type="sell">
                                Vendo {{ $up }}
                            </button>
                        </div>

                        {{-- El simulador está fijado a esta divisa --}}
                        <input type="hidden" id="sim-currency" value="{{ $cash->id }}">

                        <div class="kap-form-group">
                            <label for="sim-amount" class="kap-label">Monto (BOB)</label>
                            <input type="number" id="sim-amount" class="kap-input"
                                   placeholder="Ej: 1000" min="1" step="0.01" autocomplete="off">
                        </div>

                        <div id="sim-result" style="display:none;"></div>

                        <button type="button" class="kap-sim-btn" onclick="Kapitalya.calculate()">
                            Calcular conversión
                        </button>

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

                    <a href="{{ $waConv }}" target="_blank" rel="noopener noreferrer"
                       class="read-more mt-3 d-inline-flex" style="font-size:13px;" data-kap-cta="conv-whatsapp">
                        O escribinos directo por WhatsApp <i class="flaticon-right-arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     QUICK CONVERSION TABLE
================================================================ --}}
<section style="padding:0 0 70px;background:var(--kap-black);">
    <div class="container">
        <div style="max-width:720px;margin:0 auto;overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:480px;">
                <thead>
                    <tr style="border-bottom:1px solid var(--kap-border);">
                        <th style="text-align:left;padding:12px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-text-muted);font-weight:700;">Monto</th>
                        <th style="text-align:right;padding:12px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-gold);font-weight:700;">Comprás {{ $up }} → pagás</th>
                        <th style="text-align:right;padding:12px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-green);font-weight:700;">Vendés {{ $up }} → recibís</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([1, 5, 10, 50, 100, 500, 1000] as $qty)
                    <tr style="border-bottom:1px solid var(--kap-border);">
                        <td style="padding:13px 16px;font-weight:700;color:var(--kap-text);">{{ number_format($qty) }} {{ $up }}</td>
                        <td style="text-align:right;padding:13px 16px;font-family:'JetBrains Mono',monospace;color:var(--kap-gold);">Bs {{ number_format($cash->sell * $qty, 2) }}</td>
                        <td style="text-align:right;padding:13px 16px;font-family:'JetBrains Mono',monospace;color:var(--kap-green);">Bs {{ number_format($cash->buy * $qty, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="font-size:12px;color:var(--kap-text-muted);text-align:center;max-width:640px;margin:20px auto 0;line-height:1.6;">
            Valores calculados con la tasa de hoy (compra Bs {{ $buy4 }} · venta Bs {{ $sell4 }}). Referenciales y
            estimados; la cotización final se confirma en sucursal.
        </p>
    </div>
</section>

{{-- ================================================================
     EDITORIAL CONTENT
================================================================ --}}
<section style="padding:80px 0;background:var(--kap-surface);">
    <div class="container">
        <div class="seo-prose">
            <h2>Convertí {{ $cLabel }} a bolivianos con la tasa de hoy</h2>
            <p>
                Este convertidor usa las tasas <strong>en vivo</strong> de Tromay para pasar de {{ $cShort }}
                ({{ $up }}) a bolivianos (BOB) y viceversa. Hoy, comprar 1 {{ $up }} cuesta
                <strong>Bs {{ $sell2 }}</strong>, mientras que si vendés {{ $cShort }} te lo tomamos a
                <strong>Bs {{ $buy2 }}</strong>. La diferencia entre ambos valores es el <strong>spread</strong>, el
                margen comercial habitual de cualquier casa de cambio.
            </p>

            <h3>¿Comprar o vender {{ $cShort }}?</h3>
            <p>
                Si tenés bolivianos y querés hacerte de {{ $cShort }}, usás la tasa de <strong>venta</strong>
                (Bs {{ $sell2 }} por unidad). Si en cambio traés {{ $cShort }} y querés bolivianos, aplicamos la tasa de
                <strong>compra</strong> (Bs {{ $buy2 }} por unidad). En el simulador de arriba elegís la pestaña según lo
                que necesités y el resultado se calcula al instante, con un botón para confirmar la operación por
                WhatsApp.
            </p>

            <h3>Tasas en vivo, sin sorpresas</h3>
            <p>
                Las cotizaciones se actualizan en tiempo real desde <strong>Forex ERP</strong> y se refrescan solas en
                esta página. Como el mercado se mueve durante el día, la cotización definitiva se confirma al momento en
                la sucursal de Tromay, en <strong>Av. Las Delicias Nro. 207-C, Zona Villa Fátima, La Paz</strong>.
                Atendemos de lunes a sábado de 08:00 a 19:00 y domingos de 08:00 a 13:00. Somos una casa de cambio física
                con más de 30 años de trayectoria, registrada en SEPREC (matrícula 670400030).
            </p>

            <p style="margin-top:24px;">
                <a href="{{ route('dolar-hoy') }}" style="color:var(--kap-green);font-weight:700;">← Ver el dólar hoy en Bolivia</a>
                &nbsp;·&nbsp;
                <a href="{{ route('dinero.show', $cash->id) }}" style="color:var(--kap-gold);font-weight:700;">Ver el detalle e historial de {{ $up }} →</a>
            </p>
        </div>
    </div>
</section>

@endsection
