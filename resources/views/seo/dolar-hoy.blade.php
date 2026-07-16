@extends('layout.master')

@php
    $usdBuy2  = number_format($dollar->buy, 2);
    $usdSell2 = number_format($dollar->sell, 2);
    $usdBuy4  = number_format($dollar->buy, 4);
    $usdSell4 = number_format($dollar->sell, 4);
    $usdOfi   = $dollar->oficial > 0 ? number_format($dollar->oficial, 2) : null;
    $hoyFecha = \Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y');

    $seoDesc = "¿Cuánto está el dólar hoy en Bolivia? El dólar (USD) cotiza a Bs {$usdBuy2} la compra "
        . "y Bs {$usdSell2} la venta en Tromay, la casa de cambio física #1 de La Paz. Tasas en vivo, "
        . "actualizadas desde Forex ERP.";

    // WhatsApp CTA pre-rellenado
    $waDolar = 'https://api.whatsapp.com/send?phone=59164082967&text='
        . rawurlencode('Hola Tromay, quiero cotizar el dólar hoy. ¿Me confirman la tasa de compra y venta?');

    // Banderas / etiquetas (reusa el mapa del sitio)
    $seoFlagMap = ['usd'=>'estados unidos.png','eur'=>'union europea.png','clp'=>'chile.png','pen'=>'peru.png','brl'=>'brazil.png','ars'=>'argentina.png'];
@endphp

@section('title', 'Dólar Hoy en Bolivia · Tipo de Cambio USD en Vivo')
@section('description', $seoDesc)

@section('body')

{{-- ================================================================
     JSON-LD — FinancialProduct (USD) + FAQPage + BreadcrumbList
================================================================ --}}
@php
    $seoBase = rtrim(url('/'), '/');
    $seoLd = [
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'    => 'FinancialProduct',
                '@id'      => $seoBase . '/dolar-hoy-bolivia#producto',
                'name'     => 'Dólar hoy en Bolivia — compra y venta de USD',
                'category' => 'CurrencyExchange',
                'url'      => $seoBase . '/dolar-hoy-bolivia',
                'provider' => ['@type' => 'FinancialService', 'name' => 'Tromay Casa de Cambio', 'url' => $seoBase],
                'offers'   => [
                    '@type'         => 'Offer',
                    'itemOffered'   => ['@type' => 'Service', 'name' => 'Compra y venta de dólar estadounidense (USD)'],
                    'priceCurrency' => 'BOB',
                    'priceSpecification' => [
                        ['@type' => 'UnitPriceSpecification', 'name' => 'Compra USD', 'price' => number_format((float) $dollar->buy, 6, '.', ''),  'priceCurrency' => 'BOB'],
                        ['@type' => 'UnitPriceSpecification', 'name' => 'Venta USD',  'price' => number_format((float) $dollar->sell, 6, '.', ''), 'priceCurrency' => 'BOB'],
                    ],
                ],
            ],
            [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => $seoBase],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Dólar hoy en Bolivia', 'item' => $seoBase . '/dolar-hoy-bolivia'],
                ],
            ],
            [
                '@type' => 'FAQPage',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name'  => '¿Cuánto está el dólar hoy en Bolivia?',
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => "Hoy el dólar estadounidense (USD) cotiza en Tromay a Bs {$usdBuy2} para la compra y Bs {$usdSell2} para la venta. Son tasas referenciales actualizadas en vivo desde Forex ERP; la cotización final se confirma en sucursal."],
                    ],
                    [
                        '@type' => 'Question',
                        'name'  => '¿Qué es el dólar paralelo?',
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'El dólar paralelo es el precio al que realmente se compra y vende el dólar en el mercado, fuera del tipo de cambio oficial fijado por el Banco Central de Bolivia. Refleja la oferta y la demanda del momento, por eso suele diferir del valor oficial.'],
                    ],
                    [
                        '@type' => 'Question',
                        'name'  => '¿Dónde cambiar dólares en La Paz?',
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'En la sucursal física de Tromay, en Av. Las Delicias Nro. 207-C, Zona Villa Fátima, La Paz. Atención de lunes a sábado de 08:00 a 19:00 y domingos de 08:00 a 13:00. También podés cotizar por WhatsApp al +591 64082967.'],
                    ],
                    [
                        '@type' => 'Question',
                        'name'  => '¿Cada cuánto se actualiza el tipo de cambio?',
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Las tasas se actualizan en vivo desde Forex ERP varias veces por hora. Esta página muestra el último valor disponible y se refresca automáticamente sin necesidad de recargar.'],
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
    .seo-faq details{border:1px solid var(--kap-border);border-radius:12px;padding:16px 18px;margin-bottom:12px;background:var(--kap-surface);}
    .seo-faq summary{cursor:pointer;font-weight:700;color:var(--kap-text);font-size:16px;list-style:none;}
    .seo-faq summary::-webkit-details-marker{display:none;}
    .seo-faq summary::after{content:'+';float:right;color:var(--kap-green);font-weight:700;}
    .seo-faq details[open] summary::after{content:'–';}
    .seo-faq p{margin:12px 0 0;}
</style>

{{-- Page header --}}
<div class="kap-detail-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="kap-detail-label">Tipo de cambio · Bolivia</div>
                <h1 class="kap-detail-h1">Dólar hoy en Bolivia</h1>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="kap-breadcrumb">
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="active">Dólar hoy</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- ================================================================
     LIVE USD RATE
================================================================ --}}
<section style="padding:60px 0 40px;background:var(--kap-black);">
    <div class="container">
        <div class="row g-4 align-items-stretch">

            {{-- Big live USD card --}}
            <div class="col-lg-7">
                <div class="kap-sim-card h-100" data-kap-id="{{ $dollar->id }}">
                    <div class="d-flex align-items-center gap-3 mb-4" style="padding-bottom:18px;border-bottom:1px solid var(--kap-border);">
                        <img src="{{ url('assets/images/estados unidos.png') }}" alt="Dólar Estadounidense"
                             style="width:54px;height:54px;border-radius:8px;object-fit:cover;">
                        <div>
                            <h2 style="font-size:22px!important;margin:0 0 2px!important;">Dólar Estadounidense (USD)</h2>
                            <span class="kap-live-badge">En vivo</span>
                            <span data-kap-timestamp class="kap-timestamp">Actualizado ahora mismo</span>
                        </div>
                    </div>

                    <p style="color:var(--kap-text-muted);font-size:15px;margin:0 0 18px;line-height:1.7;">
                        Hoy <strong style="color:var(--kap-text);">{{ $hoyFecha }}</strong>, el dólar en Tromay cotiza a
                        <strong style="color:var(--kap-green);">Bs {{ $usdBuy2 }}</strong> para la compra y
                        <strong style="color:var(--kap-gold);">Bs {{ $usdSell2 }}</strong> para la venta.
                    </p>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Compra (te compramos USD)</div>
                                <div class="kap-rate-box__value kap-rate-box__value--green" data-kap-buy>{{ $usdBuy4 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Venta (te vendemos USD)</div>
                                <div class="kap-rate-box__value kap-rate-box__value--gold" data-kap-sell>{{ $usdSell4 }}</div>
                            </div>
                        </div>
                        @if($usdOfi)
                        <div class="col-12">
                            <div class="kap-rate-box">
                                <div class="kap-rate-box__label">Tasa oficial de referencia (BCB)</div>
                                <div class="kap-rate-box__value kap-rate-box__value--muted">{{ $usdOfi }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <a href="{{ $waDolar }}" target="_blank" rel="noopener noreferrer"
                       class="kap-sim-btn d-block text-center text-decoration-none mt-4" data-kap-cta="dolar-whatsapp"
                       style="background:#25D366;border-color:#25D366;">
                        Cotizar el dólar por WhatsApp
                    </a>
                    <p style="font-size:11px;color:var(--kap-text-muted);text-align:center;margin:12px 0 0;line-height:1.5;">
                        ⚠ Tasa referencial y estimada. La cotización real se confirma al momento de la operación en sucursal.
                    </p>
                </div>
            </div>

            {{-- Convert helper + other currencies --}}
            <div class="col-lg-5">
                <div class="kap-sim-card h-100">
                    <h3 class="kap-card-title">Convertí dólares y bolivianos</h3>
                    <p class="kap-card-subtitle">Cálculos rápidos con la tasa de hoy</p>

                    <div style="display:flex;flex-direction:column;gap:10px;margin-top:6px;">
                        <div class="kap-rate-box" style="text-align:left;">
                            <div class="kap-rate-box__label">1 USD equivale a (venta)</div>
                            <div class="kap-rate-box__value">Bs {{ $usdSell2 }}</div>
                        </div>
                        <div class="kap-rate-box" style="text-align:left;">
                            <div class="kap-rate-box__label">100 USD equivalen a (venta)</div>
                            <div class="kap-rate-box__value">Bs {{ number_format($dollar->sell * 100, 2) }}</div>
                        </div>
                        <div class="kap-rate-box" style="text-align:left;">
                            <div class="kap-rate-box__label">Bs 1.000 equivalen a (compra)</div>
                            <div class="kap-rate-box__value">
                                {{ $dollar->buy > 0 ? number_format(1000 / $dollar->buy, 2) : '—' }} USD
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('seo.convertidor', ['par' => 'usd-bob']) }}"
                       class="read-more mt-3 d-inline-flex" style="font-size:13px;">
                        Abrir el convertidor USD/BOB <i class="flaticon-right-arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     COMPARISON TABLE — todas las divisas
================================================================ --}}
<section style="padding:20px 0 70px;background:var(--kap-black);">
    <div class="container">
        <div class="text-center mb-4">
            <span class="section-title"><span>Comparativa de divisas</span></span>
            <h2 class="kap-section-h2" style="margin-top:8px;">El dólar frente a las demás monedas</h2>
            <p style="color:var(--kap-text-muted);max-width:640px;margin:12px auto 0;font-size:15px;">
                Además del dólar, en Tromay cambiás euro y las principales monedas de la región. Todas las tasas
                se actualizan en vivo desde Forex ERP.
            </p>
        </div>

        <div style="max-width:820px;margin:0 auto;overflow-x:auto;-webkit-overflow-scrolling:touch;">
            <table style="width:100%;border-collapse:collapse;min-width:560px;">
                <thead>
                    <tr style="border-bottom:1px solid var(--kap-border);">
                        <th style="text-align:left;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-text-muted);font-weight:700;">Divisa</th>
                        <th style="text-align:right;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-text-muted);font-weight:700;">Oficial <span style="font-weight:400;text-transform:none;">(ref. BCB)</span></th>
                        <th style="text-align:right;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-green);font-weight:700;">Compra</th>
                        <th style="text-align:right;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-gold);font-weight:700;">Venta</th>
                        <th style="text-align:right;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-text-muted);font-weight:700;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashes as $c)
                    @php
                        $ck = strtolower($c->name);
                        $cf = $seoFlagMap[$ck] ?? null;
                        $cl = $meta[$ck]['label'] ?? $c->name;
                    @endphp
                    <tr data-kap-id="{{ $c->id }}" style="border-bottom:1px solid var(--kap-border);">
                        <td style="padding:16px;">
                            <span style="display:inline-flex;align-items:center;gap:10px;">
                                @if($cf)
                                <img src="{{ url('assets/images/'.$cf) }}" alt="{{ e($cl) }}" style="width:22px;height:22px;border-radius:4px;object-fit:cover;flex-shrink:0;">
                                @endif
                                <span>
                                    <span style="font-weight:700;color:var(--kap-text);display:block;">{{ strtoupper($ck) }}</span>
                                    <span style="font-size:12px;color:var(--kap-text-muted);">{{ $cl }}</span>
                                </span>
                            </span>
                        </td>
                        <td style="text-align:right;padding:16px;font-family:'JetBrains Mono',monospace;color:var(--kap-text-muted);">
                            {{ $c->oficial > 0 ? number_format($c->oficial, 4) : '—' }}
                        </td>
                        <td style="text-align:right;padding:16px;font-family:'JetBrains Mono',monospace;color:var(--kap-green);font-weight:600;">
                            <span data-kap-buy>{{ number_format($c->buy, 4) }}</span>
                        </td>
                        <td style="text-align:right;padding:16px;font-family:'JetBrains Mono',monospace;color:var(--kap-gold);font-weight:600;">
                            <span data-kap-sell>{{ number_format($c->sell, 4) }}</span>
                        </td>
                        <td style="text-align:right;padding:16px;">
                            <a href="{{ route('seo.convertidor', ['par' => $ck.'-bob']) }}" style="font-size:13px;color:var(--kap-green);font-weight:600;text-decoration:none;white-space:nowrap;">Convertir →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="font-size:12px;color:var(--kap-text-muted);text-align:center;max-width:640px;margin:22px auto 0;line-height:1.6;">
            La <strong>tasa oficial</strong> es referencial del Banco Central de Bolivia. Nuestras tasas de compra y
            venta se actualizan en vivo desde Forex ERP; la cotización final se confirma en sucursal.
        </p>
    </div>
</section>

{{-- ================================================================
     EDITORIAL CONTENT
================================================================ --}}
<section style="padding:80px 0;background:var(--kap-surface);">
    <div class="container">
        <div class="seo-prose">

            <h2>¿Cuánto está el dólar hoy en Bolivia?</h2>
            <p>
                Al <strong>{{ $hoyFecha }}</strong>, el dólar estadounidense en Tromay se cotiza a
                <strong>Bs {{ $usdBuy2 }}</strong> para la compra y <strong>Bs {{ $usdSell2 }}</strong> para la venta.
                Esto significa que, si traés dólares, te los compramos a Bs {{ $usdBuy2 }} cada uno; y si necesitás
                comprar dólares, te los vendemos a Bs {{ $usdSell2 }}. Los valores que ves en esta página se actualizan
                solos, en vivo, sin que tengas que recargar.
            </p>

            <h3>¿Qué es el dólar paralelo y por qué difiere del oficial?</h3>
            <p>
                En Bolivia conviven dos referencias. Por un lado está el <strong>tipo de cambio oficial</strong> que
                fija el Banco Central de Bolivia (BCB){{ $usdOfi ? ', hoy en torno a Bs '.$usdOfi.' por dólar' : '' }};
                por otro, el llamado <strong>dólar paralelo</strong>, que es el precio al que efectivamente se compra y
                vende la divisa en el mercado según la oferta y la demanda del momento. Cuando la demanda de dólares
                sube, el precio de mercado tiende a separarse del oficial. Por eso una casa de cambio publica tasas de
                compra y de venta propias, que reflejan las condiciones reales del día.
            </p>

            <h3>¿Cómo se forma nuestra tasa?</h3>
            <p>
                Nuestras cotizaciones no se ponen "a dedo": provienen en tiempo real de <strong>Forex ERP</strong>, el
                motor que digitaliza la casa de cambio y consolida datos de mercado de varias fuentes. Sobre ese valor
                de referencia se aplica un <strong>spread</strong> comercial —la diferencia entre compra y venta— que es
                como opera cualquier casa de cambio del mundo. La tasa que ves publicada es la misma que usamos como
                referencia en el mostrador, sin comisiones ocultas ni letra chica.
            </p>

            <h3>Actualización en vivo, todo el día</h3>
            <p>
                El mercado cambiario se mueve durante la jornada, así que las tasas de esta página se refrescan
                automáticamente varias veces por hora. El indicador "Actualizado" te muestra hace cuánto se recibió el
                último valor. Aun así, como el mercado es dinámico, la cotización definitiva de tu operación siempre se
                confirma al momento en la sucursal.
            </p>

            <h3>¿Dónde cambiar dólares en La Paz?</h3>
            <p>
                En Tromay Casa de Cambio, la casa de cambio física con más de <strong>30 años</strong> de trayectoria en
                La Paz. Nos encontrás en <strong>Av. Las Delicias Nro. 207-C, Zona Villa Fátima</strong>, con atención de
                lunes a sábado de 08:00 a 19:00 y domingos de 08:00 a 13:00. Somos una empresa registrada en SEPREC
                (matrícula 670400030). Podés acercarte a la sucursal o escribirnos por
                <a href="{{ $waDolar }}" target="_blank" rel="noopener noreferrer" style="color:var(--kap-green);font-weight:700;">WhatsApp</a>
                para cotizar antes de venir.
            </p>

            {{-- FAQ visible (espeja el FAQPage JSON-LD) --}}
            <h2 style="margin-top:40px;">Preguntas frecuentes</h2>
            <div class="seo-faq" style="margin-top:16px;">
                <details open>
                    <summary>¿Cuánto está el dólar hoy en Bolivia?</summary>
                    <p>Hoy el dólar (USD) cotiza en Tromay a Bs {{ $usdBuy2 }} la compra y Bs {{ $usdSell2 }} la venta. Son tasas referenciales en vivo desde Forex ERP; la cotización final se confirma en sucursal.</p>
                </details>
                <details>
                    <summary>¿Qué es el dólar paralelo?</summary>
                    <p>Es el precio al que realmente se compra y vende el dólar en el mercado, fuera del tipo de cambio oficial del BCB. Refleja la oferta y la demanda del momento, por eso suele diferir del valor oficial.</p>
                </details>
                <details>
                    <summary>¿Dónde cambiar dólares en La Paz?</summary>
                    <p>En la sucursal física de Tromay, Av. Las Delicias Nro. 207-C, Zona Villa Fátima, La Paz. Atención de lunes a sábado 08:00–19:00 y domingos 08:00–13:00. También cotizás por WhatsApp al +591 64082967.</p>
                </details>
                <details>
                    <summary>¿Cada cuánto se actualiza el tipo de cambio?</summary>
                    <p>Las tasas se actualizan en vivo desde Forex ERP varias veces por hora. Esta página muestra el último valor disponible y se refresca sola, sin recargar.</p>
                </details>
            </div>

        </div>
    </div>
</section>

@endsection
