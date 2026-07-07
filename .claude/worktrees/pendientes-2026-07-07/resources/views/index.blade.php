@extends('layout.master')
@section('title', 'Más de 30 años evolucionando hacia el futuro')

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
                            <span class="top-title">Servicios Integrales · La Paz, Bolivia</span>
                            <h1>
                                Más de 30 años<br>
                                <span class="text-green">evolucionando</span>
                                hacia el futuro
                            </h1>
                            <p>
                                De Tromay Casa de Cambios a Kapitalya Servicios Integrales.
                                Experiencia, modernización y tecnología al servicio de personas,
                                emprendedores y microempresas bolivianas.
                            </p>
                            <div class="banner-btn">
                                <a href="{{ route('home') }}#servicios" class="default-btn">
                                    <span>Nuestros Servicios</span>
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
                                <span class="sv">8+</span>
                                <span class="sl">Áreas de servicio</span>
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
                                <span>Desde los <strong>años 90</strong></span>
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
            Más de 30 años cambiando divisas en <span class="kap-hl-green">La Paz, Bolivia</span>
        </p>
    </div>
</div>

{{-- ================================================================
     FEATURES — Por qué Kapitalya
================================================================ --}}
<section class="feature-area feature-area-four pb-70 pt-100">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span>¿Por qué Kapitalya?</span>
            <h2>Múltiples servicios, una sola empresa</h2>
        </div>
        <div class="row">

            <div class="col-lg-4 col-sm-6 kap-fade-up">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-reliability"></i>
                            <h3>30+ Años de Trayectoria</h3>
                        </div>
                        <p>Desde los años 90 operando en Bolivia. Décadas de experiencia comercial y financiera que respaldan cada servicio que ofrecemos.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.08s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-testing"></i>
                            <h3>Servicios Integrales</h3>
                        </div>
                        <p>Comercio, recargas, giros nacionales, consultoría y tecnología en un solo lugar. Soluciones completas para tu negocio.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-user"></i>
                            <h3>Atención Personalizada</h3>
                        </div>
                        <p>Sin burocracia innecesaria. Trato directo y cercano adaptado a las necesidades reales de cada cliente.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.08s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-database"></i>
                            <h3>Tasas Competitivas</h3>
                        </div>
                        <p>Mejores tasas del mercado actualizadas en tiempo real. Control total de tus operaciones cambiarias con transparencia total.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-application"></i>
                            <h3>Enfoque Tecnológico</h3>
                        </div>
                        <p>Herramientas digitales, automatización y plataformas modernas para optimizar cada proceso de tu operación.</p>
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
                                 alt="{{ e($cash->name) }}">
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
                        <h2>Más que un servicio.<br>Una evolución de 30 años.</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 kap-fade-up">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3><a href="{{ route('about') }}">De Tromay a Kapitalya</a></h3>
                                    <p>Desde los años 90 bajo el nombre "Tromay Casa de Cambios", evolucionamos durante tres décadas hasta convertirnos en Kapitalya Servicios Integrales — registrada en SEPREC en junio 2025.</p>
                                    <span>Desde los años 90</span>
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
                                    <p>Consultanos por WhatsApp o teléfono: <strong>+591 64082967</strong>. Respuesta rápida y atención personalizada.</p>
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
                                 alt="{{ e($latest->name) }}">
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
                "Más de 30 años evolucionando hacia el futuro."
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
