@extends('layout.master')
@section('title', 'La casa de cambio física #1 de Bolivia')
@section('description', 'Tromay, la casa de cambio física número uno de Bolivia. Décadas de trayectoria en La Paz con las mejores tasas de dólar, euro y monedas de la región, en vivo desde forex.')

@section('body')

{{-- ================================================================
     HERO — Kapitalya
================================================================ --}}
<section class="banner-area banner-area-four jarallax" data-jarallax='{"speed": 0.25}'>
    <div class="kap-hero-gold-glow"></div>
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="row align-items-center">

                    {{-- Left: headline + CTA --}}
                    <div class="col-lg-7">
                        <div class="banner-content wow fadeInDown" data-wow-delay="0.2s">
                            <span class="top-title">Casa de Cambio Física · La Paz, Bolivia</span>
                            <h1>
                                La casa de cambio física<br>
                                <span class="text-green">número uno</span>
                                de Bolivia
                            </h1>
                            <p>
                                Décadas cambiando divisas en La Paz con las mejores tasas del
                                mercado y atención personalizada en sucursal. Hoy Tromay impulsa todo
                                el <strong>ecosistema fintech Kapitalya</strong>: la confianza de siempre,
                                potenciada por tecnología de punta.
                            </p>
                            <div class="banner-btn">
                                <a href="https://api.whatsapp.com/send?phone=59164082967&text=Hola%20Tromay%2C%20quiero%20cotizar%20una%20operaci%C3%B3n%20de%20cambio"
                                   target="_blank" rel="noopener noreferrer" class="default-btn"
                                   style="background:#25D366;border-color:#25D366;">
                                    <span>Cotizar por WhatsApp</span>
                                </a>
                                <a href="{{ route('quote') }}" class="kap-btn-ghost">
                                    Ver cotizaciones <i class="bx bx-right-arrow-alt"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Hero stats --}}
                        <div class="kap-hero-stats wow fadeInUp" data-wow-delay="0.5s">
                            <div class="kap-hero-stat">
                                <span class="sv green" data-kap-count="30" data-suffix="+">30+</span>
                                <span class="sl">Años de trayectoria</span>
                            </div>
                            <div class="kap-hero-stat">
                                <span class="sv" data-kap-count="5000" data-suffix="+">5,000+</span>
                                <span class="sl">Clientes atendidos</span>
                            </div>
                            <div class="kap-hero-stat">
                                <span class="sv">6</span>
                                <span class="sl">Divisas cotizadas en vivo</span>
                            </div>
                        </div>

                        {{-- Trust bar --}}
                        <div class="kap-trust-bar wow fadeInUp" data-wow-delay="0.7s">
                            <div class="kap-trust-item">
                                <i class="bx bx-check-shield"></i>
                                <span>Registro <strong>SEPREC</strong></span>
                            </div>
                            <div class="kap-trust-item">
                                <i class="bx bx-time-five"></i>
                                <span>Tasas <strong>en vivo</strong></span>
                            </div>
                            <div class="kap-trust-item">
                                <i class="bx bx-buildings"></i>
                                <span>Trayectoria de <strong>décadas</strong></span>
                            </div>
                            <div class="kap-trust-item">
                                <i class="bx bx-map-pin"></i>
                                <span>La Paz, <strong>Bolivia</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Live Rate Panel --}}
                    <div class="col-lg-5 wow fadeInRight" data-wow-delay="0.3s">
                        <div class="kap-live-panel">
                            <div class="kap-panel-head">
                                <h4>Tasas Referenciales</h4>
                                <span class="kap-live-badge">Estimado</span>
                            </div>

                            @php
                            $kapFlagMap = [
                                'usd' => 'estados unidos.png',
                                'eur' => 'union europea.png',
                                'clp' => 'chile.png',
                                'pen' => 'peru.png',
                                'brl' => 'brazil.png',
                                'ars' => 'argentina.png',
                            ];
                            @endphp
                            @forelse($cashes as $cash)
                            @php $flagFile = $kapFlagMap[strtolower($cash->name)] ?? null; @endphp
                            <div class="kap-rate-row" data-kap-id="{{ $cash->id }}">
                                <div class="kap-rc">
                                    <div class="kap-rc-flag">
                                        @if($flagFile)
                                        <img src="{{ url('assets/images/' . $flagFile) }}"
                                             alt="{{ $cash->name }}"
                                             style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                                        @else
                                        <span style="font-size:18px;">💱</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="kap-rc-code">{{ Str::upper(Str::limit($cash->name, 3, '')) }}</span>
                                        <span class="kap-rc-name">{{ $cash->name }}</span>
                                    </div>
                                </div>
                                <div class="kap-rv">
                                    <div class="kap-rv-item">
                                        <div class="lbl">Compra</div>
                                        <div class="val buy" data-kap-buy>{{ number_format($cash->buy, 4) }}</div>
                                        <span class="kap-trend" data-kap-trend-buy></span>
                                    </div>
                                    <div class="kap-rv-item">
                                        <div class="lbl">Venta</div>
                                        <div class="val sell" data-kap-sell>{{ number_format($cash->sell, 4) }}</div>
                                        <span class="kap-trend" data-kap-trend-sell></span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <p style="color:var(--kap-text-muted);font-size:13px;text-align:center;padding:20px 0;">
                                Sin divisas disponibles en este momento.
                            </p>
                            @endforelse

                            <p style="font-size:10px;color:var(--kap-text-muted);text-align:center;margin:14px 0 0;line-height:1.5;">
                                ⚠ Tasas referenciales y estimadas. Las cotizaciones reales se confirman en sucursal.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     TICKER BAR — scrolling rates
================================================================ --}}
@if($cashes->count())
<div class="kap-ticker-bar">
    <div class="kap-ticker-track">
        @php
        $tickerFlagMap = ['usd'=>'estados unidos.png','eur'=>'union europea.png','clp'=>'chile.png','pen'=>'peru.png','brl'=>'brazil.png','ars'=>'argentina.png'];
        @endphp
        @foreach($cashes as $cash)
        @php $tFlag = $tickerFlagMap[strtolower($cash->name)] ?? null; @endphp
        <span class="kap-ticker-item">
            @if($tFlag)
            <img src="{{ url('assets/images/' . $tFlag) }}" alt="{{ $cash->name }}" style="width:16px;height:16px;border-radius:3px;object-fit:cover;flex-shrink:0;">
            @endif
            <span class="t-code">{{ Str::upper(Str::limit($cash->name, 3, '')) }}</span>
            <span class="t-sep">/</span>
            <span style="font-size:10px;color:var(--kap-text-muted)">C</span>
            <span class="t-buy" data-tick-buy="{{ $cash->id }}">{{ number_format($cash->buy, 4) }}</span>
            <span style="font-size:10px;color:var(--kap-text-muted)">V</span>
            <span class="t-sell" data-tick-sell="{{ $cash->id }}">{{ number_format($cash->sell, 4) }}</span>
        </span>
        @endforeach
    </div>
</div>
@endif

{{-- ================================================================
     PHOTO STRIP — Visual de la empresa
================================================================ --}}
<div class="kap-photo-strip">
    <img src="{{ url('assets/images/index/exchange.jpg') }}"
         alt="Kapitalya — Casa de Cambios La Paz">
    <div class="kap-photo-strip-overlay">
        <p class="kap-photo-strip-text">
            Décadas cambiando divisas en <span class="kap-hl-green">La Paz, Bolivia</span>
        </p>
    </div>
</div>

{{-- ================================================================
     FEATURES — Por qué Kapitalya
================================================================ --}}
<section class="feature-area feature-area-four pb-70 pt-100">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span>¿Por qué somos la #1?</span>
            <h2>La casa de cambio física líder de Bolivia</h2>
        </div>
        <div class="row">

            <div class="col-lg-4 col-sm-6 kap-fade-up">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-reliability"></i>
                            <h3>30+ Años de Trayectoria</h3>
                        </div>
                        <p>Décadas cambiando divisas en La Paz. Una reputación que ninguna casa de cambio nueva puede igualar.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.08s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-database"></i>
                            <h3>Las Mejores Tasas del Mercado</h3>
                        </div>
                        <p>Cotizaciones competitivas para dólar, euro y monedas de la región. Comprás y vendés siempre al mejor precio del día.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-user"></i>
                            <h3>Atención en Sucursal</h3>
                        </div>
                        <p>Trato directo, cara a cara, en nuestra sucursal de Villa Fátima. La confianza de operar en persona, sin intermediarios ni apps dudosas.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.08s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-application"></i>
                            <h3>Tasas en Vivo con Tecnología Forex</h3>
                        </div>
                        <p>Nuestras tasas se actualizan en tiempo real desde <strong>Forex ERP</strong>, con datos multi-fuente del mercado. Transparencia total, sin sorpresas.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-testing"></i>
                            <h3>Sin Comisiones Ocultas</h3>
                        </div>
                        <p>La tasa que ves es la que aplicás. Spread claro y competitivo, sin cargos escondidos ni letra chica en tu operación.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.24s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-money"></i>
                            <h3>Empresa Registrada</h3>
                        </div>
                        <p>Registrada en SEPREC (matrícula 670400030). Operamos con total transparencia dentro del marco regulatorio boliviano.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     CURRENCIES GRID — Divisas disponibles
================================================================ --}}
<section class="services-area pt-100 pb-70">
    <div class="container">
        <div class="section-title">
            <span>Tipo de Cambio · Tasas referenciales</span>
            <h2>Divisas disponibles</h2>
        </div>
        <p class="kap-section-disclaimer">
            ⚠ Las tasas publicadas son <strong class="kap-hl-gold">estimadas y referenciales</strong>.
            Las cotizaciones reales se confirman al momento de la operación en sucursal.
        </p>
        <p class="kap-rates-updated" style="text-align:center;margin:-4px 0 26px;font-size:13px;color:var(--kap-text-muted,#8a90a6);">
            <span aria-hidden="true" style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#10b981;margin-right:6px;vertical-align:middle;animation:kap-pulse 1.6s ease-in-out infinite;"></span>
            <span data-kap-timestamp>Actualizado ahora mismo</span>
            · en vivo desde Forex ERP
        </p>
        <style>@keyframes kap-pulse{0%,100%{opacity:1}50%{opacity:.35}}</style>

        {{-- SEO — tasas de cambio como datos estructurados (schema.org) --}}
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'FinancialProduct',
            'name'     => 'Cambio de divisas — Tromay Casa de Cambio',
            'category' => 'CurrencyExchange',
            'url'      => rtrim(url('/'), '/') . '/#tipo-de-cambio',
            'provider' => ['@type' => 'FinancialService', 'name' => 'Tromay Casa de Cambio', 'url' => rtrim(url('/'), '/')],
            'offers'   => collect($cashes)->map(fn ($c) => [
                '@type'         => 'Offer',
                'itemOffered'   => ['@type' => 'Service', 'name' => 'Compra y venta de ' . strtoupper($c->name)],
                'priceCurrency' => 'BOB',
                'priceSpecification' => [
                    ['@type' => 'UnitPriceSpecification', 'name' => 'Compra ' . strtoupper($c->name), 'price' => number_format((float) $c->buy, 6, '.', ''),  'priceCurrency' => 'BOB'],
                    ['@type' => 'UnitPriceSpecification', 'name' => 'Venta ' . strtoupper($c->name),  'price' => number_format((float) $c->sell, 6, '.', ''), 'priceCurrency' => 'BOB'],
                ],
            ])->values()->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <div class="text-center kap-fade-up" style="margin:-6px 0 26px;display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
            <a href="{{ route('dolar-hoy') }}" class="kap-btn-ghost">Ver el dólar hoy en Bolivia <i class="bx bx-right-arrow-alt"></i></a>
            <a href="{{ route('seo.convertidor', ['par' => 'usd-bob']) }}" class="kap-btn-ghost">Convertidor USD/BOB</a>
            <a href="{{ route('seo.convertidor', ['par' => 'eur-bob']) }}" class="kap-btn-ghost">Convertidor EUR/BOB</a>
        </div>

        @php
        $kapFlagImgMap = [
            'usd' => ['flag' => 'estados unidos.png', 'label' => 'Dólar Estadounidense'],
            'eur' => ['flag' => 'union europea.png',  'label' => 'Euro'],
            'clp' => ['flag' => 'chile.png',           'label' => 'Peso Chileno'],
            'pen' => ['flag' => 'peru.png',            'label' => 'Sol Peruano'],
            'brl' => ['flag' => 'brazil.png',          'label' => 'Real Brasileño'],
            'ars' => ['flag' => 'argentina.png',       'label' => 'Peso Argentino'],
        ];
        @endphp
        <div class="row g-3">
            @forelse($cashes as $cash)
            @php
                $key  = strtolower($cash->name);
                $meta = $kapFlagImgMap[$key] ?? null;
            @endphp
            <div class="col-lg-4 col-md-6 kap-fade-up">
                <a href="{{ route('dinero.show', $cash->id) }}"
                   class="kap-currency-card"
                   data-kap-id="{{ $cash->id }}">
                    <div class="kap-currency-card__left">
                        <div class="kap-currency-card__flag">
                            @if($meta)
                            <img src="{{ url('assets/images/' . $meta['flag']) }}"
                                 alt="{{ e($cash->name) }}"
                                 width="48" height="48" loading="lazy" decoding="async">
                            @else
                            <span style="font-size:22px;">💱</span>
                            @endif
                        </div>
                        <div style="min-width:0;">
                            <div class="kap-currency-card__code">
                                {{ Str::upper(Str::limit($cash->name, 3, '')) }}
                            </div>
                            <span class="kap-currency-card__name">
                                {{ $meta['label'] ?? e($cash->name) }}
                            </span>
                        </div>
                    </div>
                    <div class="kap-currency-card__rates">
                        <div class="kap-currency-card__rate kap-currency-card__rate--buy">
                            <span class="kap-currency-card__rate-label">Compra</span>
                            <span class="kap-currency-card__rate-value" data-kap-buy>
                                {{ number_format($cash->buy, 4) }}
                            </span>
                        </div>
                        <div class="kap-currency-card__rate kap-currency-card__rate--sell">
                            <span class="kap-currency-card__rate-label">Venta</span>
                            <span class="kap-currency-card__rate-value" data-kap-sell>
                                {{ number_format($cash->sell, 4) }}
                            </span>
                        </div>
                    </div>
                    <i class="bx bx-chevron-right kap-currency-card__arrow"></i>
                </a>
            </div>
            @empty
            <div class="col-12">
                <p style="color:var(--kap-text-muted);text-align:center;">
                    No hay divisas disponibles en este momento.
                </p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@include('partials.lead-capture', ['leadSource' => 'home'])

{{-- ================================================================
     TRANSPARENCIA — Tasa oficial vs. nuestra tasa
================================================================ --}}
<section id="transparencia" style="background:var(--kap-black);padding:100px 0 80px;">
    <div class="container">

        <div class="text-center mb-5 kap-fade-up">
            <span class="section-title"><span>Transparencia total</span></span>
            <h2 class="kap-section-h2" style="margin-top:8px;">
                Tasa oficial vs. <span class="kap-hl-green">nuestra tasa</span>
            </h2>
            <p class="kap-section-body--lg" style="max-width:600px;margin:16px auto 0;">
                Ponemos la tasa oficial de referencia del BCB al lado de la nuestra. Sin letra chica:
                la tasa que ves es la que aplicás, actualizada en vivo desde Forex ERP.
            </p>
        </div>

        @php
        $kapCmpFlags = ['usd'=>'estados unidos.png','eur'=>'union europea.png','clp'=>'chile.png','pen'=>'peru.png','brl'=>'brazil.png','ars'=>'argentina.png'];
        @endphp

        @if($cashes->count())
        <div class="kap-fade-up" style="max-width:820px;margin:0 auto;overflow-x:auto;-webkit-overflow-scrolling:touch;">
            <table style="width:100%;border-collapse:collapse;min-width:560px;">
                <thead>
                    <tr style="border-bottom:1px solid var(--kap-border);">
                        <th style="text-align:left;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-text-muted);font-weight:700;">Divisa</th>
                        <th style="text-align:right;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-text-muted);font-weight:700;">Oficial <span style="font-weight:400;text-transform:none;">(ref. BCB)</span></th>
                        <th style="text-align:right;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-green);font-weight:700;">Compra</th>
                        <th style="text-align:right;padding:14px 16px;font-size:12px;text-transform:uppercase;letter-spacing:.5px;color:var(--kap-gold);font-weight:700;">Venta</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashes as $cash)
                    @php $cf = $kapCmpFlags[strtolower($cash->name)] ?? null; @endphp
                    <tr data-kap-id="{{ $cash->id }}" style="border-bottom:1px solid var(--kap-border);">
                        <td style="padding:16px;">
                            <span style="display:inline-flex;align-items:center;gap:10px;">
                                @if($cf)
                                <img src="{{ url('assets/images/'.$cf) }}" alt="{{ e($cash->name) }}" style="width:22px;height:22px;border-radius:4px;object-fit:cover;flex-shrink:0;">
                                @endif
                                <span style="font-weight:700;color:var(--kap-text);">{{ Str::upper(Str::limit($cash->name, 3, '')) }}</span>
                            </span>
                        </td>
                        <td style="text-align:right;padding:16px;font-family:'JetBrains Mono',monospace;color:var(--kap-text-muted);">
                            {{ $cash->oficial > 0 ? number_format($cash->oficial, 4) : '—' }}
                        </td>
                        <td style="text-align:right;padding:16px;font-family:'JetBrains Mono',monospace;color:var(--kap-green);font-weight:600;">
                            <span data-kap-buy>{{ number_format($cash->buy, 4) }}</span>
                        </td>
                        <td style="text-align:right;padding:16px;font-family:'JetBrains Mono',monospace;color:var(--kap-gold);font-weight:600;">
                            <span data-kap-sell>{{ number_format($cash->sell, 4) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p style="font-size:12px;color:var(--kap-text-muted);text-align:center;max-width:640px;margin:22px auto 0;line-height:1.6;">
            La <strong>tasa oficial</strong> es referencial del Banco Central de Bolivia. Nuestras tasas de
            compra y venta se actualizan en vivo desde Forex ERP; la cotización final se confirma en sucursal.
        </p>
        @else
        <p style="color:var(--kap-text-muted);text-align:center;">Sin divisas disponibles en este momento.</p>
        @endif

    </div>
</section>

@include('partials.trust')

{{-- ================================================================
     SERVICIOS INTEGRALES — Todas las áreas de negocio
================================================================ --}}
<section id="servicios" style="background:var(--kap-surface);padding:100px 0 80px;">
    <div class="container">

        <div class="text-center mb-5 kap-fade-up">
            <span class="section-title">
                <span>Nuestros Servicios</span>
            </span>
            <h2 class="kap-section-h2" style="margin-top:8px;">
                Una empresa, ocho áreas de solución
            </h2>
            <p class="kap-section-body--lg" style="max-width:560px;margin:16px auto 0;">
                Kapitalya Servicios Integrales ofrece soluciones complementarias diseñadas para
                las necesidades reales de personas, emprendedores y microempresas bolivianas.
            </p>
        </div>

        @php
        $servicios = [
            ['icon'=>'bx-store',    'color'=>'green', 'title'=>'Servicios Comerciales',     'desc'=>'Comercialización y distribución de productos autorizados de terceros. Venta directa y gestión comercial.'],
            ['icon'=>'bx-mobile',   'color'=>'green', 'title'=>'Recargas Electrónicas',     'desc'=>'Recargas a todas las operadoras bolivianas y servicios digitales complementarios de forma rápida y segura.'],
            ['icon'=>'bx-transfer', 'color'=>'gold',  'title'=>'Giros Nacionales',           'desc'=>'Transferencias y giros de dinero a nivel nacional. Servicio de envíos seguros y confiables en toda Bolivia.'],
            ['icon'=>'bx-support',  'color'=>'green', 'title'=>'Apoyo Operativo',            'desc'=>'Soporte operativo integral para comercios y microempresas. Gestión y acompañamiento en procesos del día a día.'],
            ['icon'=>'bx-briefcase','color'=>'gold',  'title'=>'Consultoría Administrativa', 'desc'=>'Organización de procesos, procedimientos administrativos y asesoría para la gestión eficiente de tu negocio.'],
            ['icon'=>'bx-group',    'color'=>'green', 'title'=>'Gestión de Talento',         'desc'=>'Recursos humanos, selección de personal y capacitación básica orientada a microempresas y emprendimientos.'],
            ['icon'=>'bx-chip',     'color'=>'green', 'title'=>'Servicios Tecnológicos',     'desc'=>'Soporte técnico, digitalización de procesos, automatización y desarrollo de soluciones digitales a medida.'],
            ['icon'=>'bx-money',    'color'=>'gold',  'title'=>'Tipo de Cambio',             'desc'=>'Tasas competitivas y actualizadas en tiempo real para las principales divisas internacionales del mercado boliviano.'],
        ];
        @endphp
        <div class="row g-4">
            @foreach($servicios as $i => $svc)
            <div class="col-lg-3 col-md-6 kap-fade-up" @if($i > 0) style="transition-delay:{{ ($i % 4) * 0.06 }}s" @endif>
                <div class="kap-svc-card kap-svc-card--{{ $svc['color'] }}">
                    <div class="kap-svc-icon kap-svc-icon--{{ $svc['color'] }}">
                        <i class="bx {{ $svc['icon'] }}"></i>
                    </div>
                    <h4 class="kap-svc-title">{{ $svc['title'] }}</h4>
                    <p class="kap-svc-desc">{{ $svc['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5 kap-fade-up">
            <a href="{{ route('about') }}" class="kap-btn-ghost">
                Ver más sobre nosotros <i class="bx bx-right-arrow-alt"></i>
            </a>
        </div>

    </div>
</section>

@include('partials.ad', ['maxWidth' => 728])

{{-- ================================================================
     ECOSISTEMA KAPITALYA — Venta cruzada de los demás sistemas
================================================================ --}}
<section id="ecosistema" style="background:var(--kap-black);padding:100px 0 80px;position:relative;overflow:hidden;">
    <div class="kap-hero-gold-glow"></div>

    <div class="container" style="position:relative;z-index:2;">

        <div class="text-center mb-5 kap-fade-up">
            <span class="section-title"><span>Ecosistema Kapitalya</span></span>
            <h2 class="kap-section-h2" style="margin-top:8px;">
                Del mostrador físico a<br>
                <span class="kap-gradient-text">toda una plataforma fintech</span>
            </h2>
            <p class="kap-section-body--lg" style="max-width:640px;margin:16px auto 0;">
                Las tasas que ves acá provienen en tiempo real de <strong>Forex ERP</strong>, el motor
                que digitaliza nuestra casa de cambio. Y es solo la punta: Tromay es la cara visible de
                un ecosistema de productos bolivianos que podés llevar a tu propio negocio.
            </p>
        </div>

        @php
        // URLs configurables por env (patrón env('CLAVE','default')).
        // REGLA: el default SOLO puede ser un host que responda hoy. Verificado
        // 2026-08-18: forex/pay/insights/paralelo/kapitalya.com.bo -> 200;
        // proformapro y katalogo NO tienen DNS publicado (por eso apuntan al Hub)
        // y alertas fue dado de baja (redirige a Paralelo). Al publicar un
        // subdominio nuevo, sobreescribir por env ECO_*_URL.
        $ecosistema = [
            [
                'icon'=>'bx-line-chart', 'color'=>'green',
                'title'=>'Forex ERP',
                'tag'=>'Para casas de cambio',
                'desc'=>'Digitalizá tu casa de cambio: tasas multi-fuente en vivo, transacciones con antifraude, inventario y cumplimiento ASFI.',
                'url'=>env('ECO_FOREX_URL', 'https://forex.kapitalya.com.bo'),
            ],
            [
                'icon'=>'bx-wallet', 'color'=>'gold',
                'title'=>'Kapitalya Pay',
                'tag'=>'Pagos digitales',
                'desc'=>'Billetera y cobros digitales para personas y comercios. Enviá, recibí y pagá en segundos dentro del ecosistema.',
                'url'=>env('ECO_PAY_URL', 'https://pay.kapitalya.com.bo'),
            ],
            [
                'icon'=>'bx-bell', 'color'=>'green',
                'title'=>'Paralelo',
                'tag'=>'Tipo de cambio',
                'desc'=>'Seguí el dólar paralelo, el USDT y el Bitcoin en Bolivia en tiempo real, con la evolución del mercado al minuto.',
                'url'=>env('ECO_PARALELO_URL', 'https://paralelo.kapitalya.com.bo'),
            ],
            [
                'icon'=>'bx-receipt', 'color'=>'green',
                'title'=>'ProformaPro',
                'tag'=>'PyMEs',
                'desc'=>'Proformas, facturación e inventario para micro y pequeñas empresas bolivianas. Simple, rápido y en la nube.',
                'url'=>env('ECO_PROFORMA_URL', 'https://kapitalya.com.bo'),
            ],
            [
                'icon'=>'bx-store-alt', 'color'=>'gold',
                'title'=>'Katálogo',
                'tag'=>'Comercio digital',
                'desc'=>'Convertí tu inventario en un catálogo digital con enlace propio para vender por WhatsApp y redes.',
                'url'=>env('ECO_KATALOGO_URL', 'https://kapitalya.com.bo'),
            ],
            [
                'icon'=>'bx-bar-chart-alt-2', 'color'=>'green',
                'title'=>'Kapitalya Insights',
                'tag'=>'Datos & análisis',
                'desc'=>'Análisis del mercado boliviano, tasas históricas e indicadores para decidir con datos, no con corazonadas.',
                'url'=>env('ECO_INSIGHTS_URL', 'https://insights.kapitalya.com.bo'),
            ],
        ];
        @endphp

        <div class="row g-4">
            @foreach($ecosistema as $i => $eco)
            <div class="col-lg-4 col-md-6 kap-fade-up" @if($i > 0) style="transition-delay:{{ ($i % 3) * 0.08 }}s" @endif>
                <a href="{{ $eco['url'] }}" target="_blank" rel="noopener"
                   class="kap-svc-card kap-svc-card--{{ $eco['color'] }}"
                   style="display:block;text-decoration:none;height:100%;">
                    <div class="kap-svc-icon kap-svc-icon--{{ $eco['color'] }}">
                        <i class="bx {{ $eco['icon'] }}"></i>
                    </div>
                    <span style="display:inline-block;font-size:11px;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:var(--kap-{{ $eco['color'] }});margin-bottom:6px;">
                        {{ $eco['tag'] }}
                    </span>
                    <h4 class="kap-svc-title" style="display:flex;align-items:center;gap:6px;">
                        {{ $eco['title'] }}
                        <i class="bx bx-right-top-arrow-circle" style="font-size:16px;opacity:.6;"></i>
                    </h4>
                    <p class="kap-svc-desc">{{ $eco['desc'] }}</p>
                </a>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5 kap-fade-up">
            <a href="{{ env('KAPITALYA_HUB_URL', 'https://kapitalya.com.bo') }}" target="_blank" rel="noopener" class="default-btn">
                <span>Explorar todo el ecosistema</span>
            </a>
        </div>

    </div>
</section>

{{-- ================================================================
     SIMULATOR — Calculadora inteligente
================================================================ --}}
<section class="kap-sim-section pt-100 pb-100">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-5 kap-fade-up">
                <span class="top-title d-inline-block mb-3" style="position:static;">Simulador Kapitalya</span>
                <h2 class="kap-section-h2" style="margin-bottom:16px;">
                    Calcula tu operación<br>
                    <span style="color:var(--kap-green)">antes de ejecutarla</span>
                </h2>
                <p class="kap-section-body--lg">
                    Ingresá el monto, seleccioná la divisa y conocé el resultado estimado al instante.
                    Sin sorpresas, sin letra chica.
                </p>

                <div class="row mt-4">
                    <div class="col-6">
                        <div class="kap-rate-box">
                            <div class="kap-rate-box__label">Spread típico</div>
                            <div class="kap-rate-box__value kap-rate-box__value--green">0.2%</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="kap-rate-box">
                            <div class="kap-rate-box__label">Actualización</div>
                            <div class="kap-rate-box__value kap-rate-box__value--gold">60 seg</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 offset-lg-1 kap-fade-up" style="transition-delay:0.15s;">
                <div class="kap-sim-card">
                    <h3>Simulador de Tasas</h3>
                    <p>Resultado <strong>estimado y referencial</strong> — confirmá en sucursal</p>

                    <form id="kap-simulator" novalidate>
                        @csrf

                        {{-- Buy / Sell tabs --}}
                        <div class="kap-tabs">
                            <button type="button" class="kap-tab-btn active" data-type="buy">
                                Compro divisa
                            </button>
                            <button type="button" class="kap-tab-btn" data-type="sell">
                                Vendo divisa
                            </button>
                        </div>

                        {{-- Currency select --}}
                        <div class="kap-form-group">
                            <label for="sim-currency" class="kap-label">Divisa</label>
                            <select id="sim-currency" class="kap-select">
                                <option value="">— Seleccionar moneda —</option>
                                @foreach($cashes as $cash)
                                <option value="{{ $cash->id }}"
                                        data-buy="{{ $cash->buy }}"
                                        data-sell="{{ $cash->sell }}">
                                    {{ e($cash->name) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Amount --}}
                        <div class="kap-form-group">
                            <label for="sim-amount" class="kap-label">Monto (BOB)</label>
                            <input type="number"
                                   id="sim-amount"
                                   class="kap-input"
                                   placeholder="Ej: 1000"
                                   min="1"
                                   step="0.01"
                                   autocomplete="off">
                        </div>

                        {{-- Result --}}
                        <div id="sim-result" style="display:none;"></div>

                        <button type="button" class="kap-sim-btn" onclick="Kapitalya.calculate()">
                            Calcular operación
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     IDENTIDAD — Historia, Misión, Visión
================================================================ --}}
<section class="solution-area pb-70">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-6">
                <div class="solution-content">
                    <div class="solution-title">
                        <span>Nuestra historia</span>
                        <h2>Más que un servicio.<br>Una evolución de décadas.</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 kap-fade-up">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3><a href="{{ route('about') }}">De Tromay a Kapitalya</a></h3>
                                    <p>Nacimos como "Tromay Casa de Cambios" y evolucionamos durante décadas hasta convertirnos en Kapitalya Servicios Integrales — registrada en SEPREC en junio 2025.</p>
                                    <span>Trayectoria de décadas</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 kap-fade-up">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3><a href="{{ route('about') }}">Misión</a></h3>
                                    <p>Brindar soluciones comerciales, administrativas y tecnológicas de manera eficiente y confiable, ayudando al crecimiento de personas, emprendedores y microempresas bolivianas.</p>
                                    <span>Misión</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 offset-md-3 offset-lg-0 kap-fade-up">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3><a href="{{ route('about') }}">Visión</a></h3>
                                    <p>Convertirse en la empresa boliviana referente en servicios integrales e innovación, destacándose por eficiencia, confianza y transformación digital hacia el ecosistema fintech.</p>
                                    <span>Visión</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 pr-0">
                <div class="solution-img">
                    <img src="{{ url('assets/images/index/exchange.jpg') }}" alt="Kapitalya — Evolución empresarial boliviana">
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     VISIÓN DIGITAL — Transformación tecnológica
================================================================ --}}
<section style="background:var(--kap-black);padding:100px 0 80px;position:relative;overflow:hidden;">

    {{-- Background grid --}}
    <div style="position:absolute;inset:0;
                background-image:linear-gradient(var(--kap-border) 1px,transparent 1px),linear-gradient(90deg,var(--kap-border) 1px,transparent 1px);
                background-size:64px 64px;opacity:0.25;
                mask-image:radial-gradient(ellipse 80% 70% at 50% 50%,black 20%,transparent 100%);
                -webkit-mask-image:radial-gradient(ellipse 80% 70% at 50% 50%,black 20%,transparent 100%);
                pointer-events:none;"></div>

    <div class="container" style="position:relative;z-index:2;">

        <div class="row align-items-center g-5">

            <div class="col-lg-5 kap-fade-up">
                <span class="section-title"><span>Visión tecnológica</span></span>
                <h2 class="kap-section-h2" style="margin:12px 0 16px;">
                    Evolucionando hacia<br>
                    <span class="kap-gradient-text">el futuro digital</span>
                </h2>
                <p class="kap-section-body--lg" style="margin-bottom:28px;">
                    Kapitalya tiene una visión clara: transformarse progresivamente hacia servicios fintech,
                    inteligencia artificial y plataformas digitales que revolucionen la forma en que Bolivia accede
                    a los servicios empresariales.
                </p>
                <a href="{{ route('about') }}#roadmap" class="kap-btn-ghost">
                    Ver roadmap tecnológico <i class="bx bx-right-arrow-alt"></i>
                </a>
            </div>

            <div class="col-lg-7 kap-fade-up" style="transition-delay:.12s;">
                <div class="row g-3">

                    @foreach([
                        ['Fintech y servicios financieros digitales',    'bx-credit-card',    'green', 'Regulado, escalable y orientado a la inclusión financiera.'],
                        ['Inteligencia Artificial aplicada',             'bx-brain',          'gold',  'Automatización inteligente para decisiones empresariales.'],
                        ['Automatización de procesos',                   'bx-bot',            'green', 'Flujos de trabajo eficientes sin intervención manual.'],
                        ['Plataformas digitales propias',                'bx-layer',          'green', 'Ecosistemas digitales para clientes y operaciones internas.'],
                        ['Datos e inteligencia operativa',               'bx-bar-chart-alt-2','gold',  'Decisiones basadas en métricas y análisis en tiempo real.'],
                        ['Ecosistemas empresariales inteligentes',       'bx-network-chart',  'green', 'Integración de servicios en una sola infraestructura digital.'],
                    ] as $item)
                    <div class="col-md-6">
                        <div class="kap-vision-item">
                            <div class="kap-vision-icon kap-vision-icon--{{ $item[2] }}">
                                <i class="bx {{ $item[1] }}"></i>
                            </div>
                            <div>
                                <div class="kap-vision-title">{{ $item[0] }}</div>
                                <div class="kap-vision-desc">{{ $item[3] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>
</section>

{{-- ================================================================
     RECOMMENDATIONS
================================================================ --}}
<section class="protect-area protect-area-four pt-100 pb-70">
    <div class="container">
        <div class="row">

            <div class="col-lg-6">
                <div class="protect-img">
                    <img src="{{ url('assets/images/index/cambio.jpg') }}" alt="Horario Kapitalya">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="protect-content">
                    <div class="protect-title">
                        <span>Información Operativa</span>
                        <h2>Lo que necesitás saber</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-sm-6 kap-fade-up">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-database"></i>
                                    <h3>Horario de Atención</h3>
                                    <p>
                                        Lunes a Sábado<br>
                                        08:00 — 19:00<br>
                                        Domingos 08:00 — 13:00
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 kap-fade-up">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-application"></i>
                                    <h3>Sucursal Villa Fátima</h3>
                                    <p>Av. Las Delicias Nro. 207-C, Zona Villa Fátima, La Paz. Atención personalizada en nuestras instalaciones.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 kap-fade-up">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-reliability"></i>
                                    <h3>Empresa Registrada</h3>
                                    <p>Registrada en SEPREC como Empresa Unipersonal. Matrícula Nro. 670400030.  </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 kap-fade-up">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-money"></i>
                                    <h3>Contacto Directo</h3>
                                    <p>Consultanos por <a href="https://api.whatsapp.com/send?phone=59164082967&text=Hola%20Tromay%2C%20necesito%20informaci%C3%B3n" target="_blank" rel="noopener noreferrer" style="color:var(--kap-green);font-weight:700;">WhatsApp</a> o <a href="tel:+59164082967" style="color:var(--kap-gold);font-weight:700;">teléfono +591 64082967</a>. Respuesta rápida y atención personalizada.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     INSIGHTS / NOTICIAS
================================================================ --}}
@if($latests->count())
<section class="blog-area blog-area-four pb-70 pt-70">
    <div class="container">
        <div class="section-title">
            <span>Insights Kapitalya</span>
            <h2>Novedades, análisis y perspectivas del mercado</h2>
        </div>

        <div class="row">
            @foreach($latests as $latest)
            <div class="col-lg-4 col-md-6 kap-fade-up">
                <div class="single-blog">
                    <div class="blog-img">
                        <a href="{{ route('noticia.show', $latest->id) }}">
                            <img src="{{ url('assets/img/latest/' . $latest->path) }}"
                                 alt="{{ e($latest->name) }}"
                                 onerror="this.onerror=null;this.src='{{ url('assets/images/kapitalya-tromay-banner-1500x500.svg') }}'">
                        </a>
                    </div>
                    <div class="blog-content">
                        <span>
                            {{ \Carbon\Carbon::parse($latest->date_publication)->translatedFormat('M Y') }}
                        </span>
                        <h3>
                            <a href="{{ route('noticia.show', $latest->id) }}">
                                {{ e($latest->name) }}
                            </a>
                        </h3>
                        <p>{{ Str::limit(e($latest->description), 100) }}</p>
                        <a href="{{ route('noticia.show', $latest->id) }}" class="read-more">
                            Leer análisis <i class="flaticon-right-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================================================================
     BRAND MESSAGE — CTA central
================================================================ --}}
<section style="background:var(--kap-surface);padding:80px 0;">
    <div class="container">
        <div class="kap-brand-message kap-fade-up">
            <div class="kap-brand-message-text">
                "Décadas evolucionando hacia el futuro."
            </div>
            <p class="kap-brand-message-sub">
                De Tromay Casa de Cambios a Kapitalya Servicios Integrales.
                La evolución tecnológica de un legado boliviano.
            </p>
            <div class="kap-btn-row">
                <a href="{{ route('contact') }}" class="kap-btn-primary">
                    Contactar ahora
                </a>
                <a href="{{ route('about') }}" class="kap-btn-ghost">
                    Conocer nuestra historia <i class="bx bx-right-arrow-alt"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     MAP
================================================================ --}}
<section class="kap-map-section">
    <div class="container">
        <div class="kap-map-section-head">
            <div class="section-title" style="margin-bottom:0;">
                <span>¿Dónde encontrarnos?</span>
                <h2>Nuestra ubicación en La Paz</h2>
            </div>
            <p style="font-size:14px;color:var(--kap-text-muted);margin:8px 0 0;">
                Av. Las Delicias Nro. 207-C · Zona Villa Fátima · La Paz, Bolivia
            </p>
            <a href="https://maps.google.com/?q=Kapitalya+Servicios+Integrales+La+Paz+Bolivia"
               target="_blank" rel="noopener noreferrer"
               class="kap-map-directions-btn">
                <i class="bx bx-navigation"></i> Cómo llegar
            </a>
        </div>
        <div class="kap-map-wrap">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d239.12006877689288!2d-68.1210681488008!3d-16.479532316606306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sbo!4v1668611773954!5m2!1ses!2sbo"
                height="400"
                title="Ubicación Kapitalya"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>

@endsection
