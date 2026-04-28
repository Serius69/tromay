@extends('layout.master')
@section('title', 'Optimiza tu Capital con Inteligencia Financiera')

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
                            <span class="top-title">Plataforma Financiera Inteligente</span>
                            <h1>
                                Optimiza tu<br>
                                <span class="text-green">Capital</span>
                                con Precisión
                            </h1>
                            <p>
                                Decisiones basadas en datos. Control financiero en tiempo real.
                                Tecnología al servicio de tu crecimiento patrimonial.
                            </p>
                            <div class="banner-btn">
                                <a href="{{ route('quote') }}" class="default-btn">
                                    <span>Simular Operación</span>
                                </a>
                                <a href="{{ route('about') }}" class="kap-btn-ghost">
                                    Conocer más <i class="bx bx-right-arrow-alt"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Hero stats --}}
                        <div class="kap-hero-stats wow fadeInUp" data-wow-delay="0.5s">
                            <div class="kap-hero-stat">
                                <span class="sv green" data-kap-count="20" data-suffix="+">20+</span>
                                <span class="sl">Años en el mercado</span>
                            </div>
                            <div class="kap-hero-stat">
                                <span class="sv" data-kap-count="5000" data-suffix="+">5,000+</span>
                                <span class="sl">Clientes atendidos</span>
                            </div>
                            <div class="kap-hero-stat">
                                <span class="sv">{{ $cashes->count() }}</span>
                                <span class="sl">Divisas disponibles</span>
                            </div>
                        </div>

                        {{-- Trust bar --}}
                        <div class="kap-trust-bar wow fadeInUp" data-wow-delay="0.7s">
                            <div class="kap-trust-item">
                                <i class="bx bx-shield-check"></i>
                                <span><strong>HTTPS</strong> seguro</span>
                            </div>
                            <div class="kap-trust-item">
                                <i class="bx bx-time-five"></i>
                                <span>Tasas <strong>en vivo</strong></span>
                            </div>
                            <div class="kap-trust-item">
                                <i class="bx bx-buildings"></i>
                                <span>Desde <strong>1999</strong></span>
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
                                <h4>Tasas en Tiempo Real</h4>
                                <span class="kap-live-badge">En vivo</span>
                            </div>

                            @forelse($cashes->take(5) as $cash)
                            <div class="kap-rate-row" data-kap-id="{{ $cash->id }}">
                                <div class="kap-rc">
                                    <div class="kap-rc-flag">
                                        <img src="{{ url('assets/img/cash/' . $cash->path) }}"
                                             alt="{{ $cash->name }}"
                                             onerror="this.style.display='none';this.parentElement.textContent='💱'">
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
        @foreach($cashes as $cash)
        <span class="kap-ticker-item">
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
     FEATURES — Por qué Kapitalya
================================================================ --}}
<section class="feature-area feature-area-four pb-70 pt-100">
    <div class="container">
        <div class="row">

            <div class="col-lg-4 col-sm-6 kap-fade-up">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-reliability"></i>
                            <h3>Tasas Competitivas</h3>
                        </div>
                        <p>Ofrecemos las mejores tasas del mercado, actualizadas en tiempo real para maximizar el valor de tus operaciones cambiarias.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-testing"></i>
                            <h3>Análisis Inteligente</h3>
                        </div>
                        <p>Nuestro simulador avanzado te permite calcular y proyectar resultados antes de ejecutar cualquier operación financiera.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 offset-sm-3 offset-lg-0 kap-fade-up">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-user"></i>
                            <h3>+20 Años de Trayectoria</h3>
                        </div>
                        <p>Más de dos décadas consolidando confianza en el mercado cambiario boliviano, con miles de operaciones exitosas.</p>
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
            <span>Divisas disponibles</span>
            <h2>Operá con las principales monedas del mundo</h2>
        </div>

        <div class="row">
            @forelse($cashes as $cash)
            <div class="col-lg-3 col-sm-6 mb-4 kap-fade-up">
                <div class="single-services" data-kap-id="{{ $cash->id }}">
                    <div class="services-img">
                        <a href="{{ route('dinero.show', $cash->id) }}">
                            <img src="{{ url('assets/img/cash/' . $cash->path) }}" alt="{{ e($cash->name) }}">
                        </a>
                    </div>
                    <div class="services-content">
                        <h3>
                            <a href="{{ route('dinero.show', $cash->id) }}">
                                {{ e($cash->name) }}
                            </a>
                        </h3>
                        <div class="content">
                            <p>Compra: <strong data-kap-buy>{{ number_format($cash->buy, 4) }}</strong></p>
                            <p>Venta: <strong data-kap-sell>{{ number_format($cash->sell, 4) }}</strong></p>
                            @if($cash->oficial)
                            <p>Oficial: {{ number_format($cash->oficial, 4) }}</p>
                            @endif
                        </div>
                        <a href="{{ route('dinero.show', $cash->id) }}" class="read-more">
                            Ver detalles <i class="flaticon-right-arrow"></i>
                        </a>
                    </div>
                </div>
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
     SIMULATOR — Calculadora inteligente
================================================================ --}}
<section class="kap-sim-section pt-100 pb-100">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-5 kap-fade-up">
                <span class="top-title d-inline-block mb-3" style="position:static;">Simulador Kapitalya</span>
                <h2 style="font-family:var(--font-display);font-size:clamp(28px,3.5vw,42px);font-weight:800;letter-spacing:-0.025em;margin-bottom:16px;">
                    Calcula tu operación<br>
                    <span style="color:var(--kap-green)">antes de ejecutarla</span>
                </h2>
                <p style="font-size:16px;color:var(--kap-text-sec);line-height:1.7;margin-bottom:0;">
                    Ingresá el monto, seleccioná la divisa y conocé el resultado estimado al instante.
                    Sin sorpresas, sin letra chica.
                </p>

                <div class="row mt-4">
                    <div class="col-6">
                        <div style="padding:16px;background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:10px;">
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:6px;">Spread típico</div>
                            <div style="font-size:20px;font-weight:800;font-family:var(--font-mono);color:var(--kap-green);">0.2%</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="padding:16px;background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:10px;">
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:6px;">Actualización</div>
                            <div style="font-size:20px;font-weight:800;font-family:var(--font-mono);color:var(--kap-gold);">60 seg</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 offset-lg-1 kap-fade-up" style="transition-delay:0.15s;">
                <div class="kap-sim-card">
                    <h3>Simulador de Tasas</h3>
                    <p>Resultado estimado en tiempo real</p>

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
     ABOUT / IDENTIDAD
================================================================ --}}
<section class="solution-area pb-70">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-6">
                <div class="solution-content">
                    <div class="solution-title">
                        <span>Kapitalya</span>
                        <h2>Más que una casa de cambios.<br>Una plataforma financiera.</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 kap-fade-up">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3><a href="{{ route('about') }}">Historia y Trayectoria</a></h3>
                                    <p>Desde 1999, construimos confianza en el mercado cambiario boliviano. Más de dos décadas de experiencia nos posicionan como referente en optimización de divisas.</p>
                                    <span>Fundada en 1999</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 kap-fade-up">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3><a href="{{ route('about') }}">Misión</a></h3>
                                    <p>Proveer acceso a tasas de cambio competitivas y transparentes, con tecnología que simplifica la toma de decisiones financieras.</p>
                                    <span>Misión</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 offset-md-3 offset-lg-0 kap-fade-up">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3><a href="{{ route('about') }}">Visión</a></h3>
                                    <p>Ser la plataforma financiera líder en Bolivia, integrando inteligencia de mercado y análisis predictivo para maximizar el capital de nuestros clientes.</p>
                                    <span>Visión</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 pr-0">
                <div class="solution-img">
                    <img src="{{ url('assets/img/index/exchange.jpg') }}" alt="Kapitalya — Control financiero">
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
                    <img src="{{ url('assets/img/index/cambio.jpg') }}" alt="Horario Kapitalya">
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
                                        Lunes a Viernes<br>
                                        08:00 – 12:30 | 13:00 – 19:00<br>
                                        Domingos 08:00 – 12:30
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6 kap-fade-up">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-application"></i>
                                    <h3>Operaciones en Sucursal</h3>
                                    <p>Todas las operaciones se realizan exclusivamente en horario de atención y en nuestras sucursales habilitadas.</p>
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
            <span>Insights Financieros</span>
            <h2>Análisis y novedades del mercado de divisas</h2>
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
     MAP
================================================================ --}}
<div class="section-title text-center pt-70 pb-20">
    <span>¿Dónde encontrarnos?</span>
    <h2>Nuestra ubicación en La Paz</h2>
</div>
<div class="map-area">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d239.12006877689288!2d-68.1210681488008!3d-16.479532316606306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sbo!4v1668611773954!5m2!1ses!2sbo"
        title="Ubicación Kapitalya"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        allowfullscreen>
    </iframe>
</div>

@endsection
