<!doctype html>
<html lang="es" data-theme="dark">
<head>
    <script>
        (function(){var t=localStorage.getItem('kap-theme');if(t==='light')document.documentElement.setAttribute('data-theme','light');}());
    </script>
    {{-- ⬡ Google AdSense — loader en el <head> para que el crawler de AdSense VERIFIQUE el sitio
         y para Auto Ads. Debe ser rastreable (NO gated por consentimiento): si no, el panel marca
         "Requires review / Not found". Para que el consentimiento NO sea decorativo, mientras el
         usuario no haya aceptado ('kap-consent-v1' !== 'granted') se piden anuncios NO
         personalizados (requestNonPersonalizedAds=1 — sin cookies de perfilado); al aceptar, el
         bloque de consentimiento del footer lo baja a 0. El consentimiento gobierna además
         Analytics. __kapAds=1 evita que el bloque de consentimiento inyecte un 2º loader. --}}
    @if(config('services.adsense.client'))
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.adsense.client') }}" crossorigin="anonymous"></script>
    <script>
        window.__kapAds=1;
        (function(){var g=false;try{g=localStorage.getItem('kap-consent-v1')==='granted';}catch(e){}
        if(!g){(window.adsbygoogle=window.adsbygoogle||[]).requestNonPersonalizedAds=1;}}());
    </script>
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('description', 'Tromay Casa de Cambio — la casa de cambio física número uno de Bolivia. Más de 30 años en La Paz con las mejores tasas del mercado, atención en sucursal y tasas de dólar, euro y monedas de la región en tiempo real. Corazón del ecosistema fintech Kapitalya.')">
    <meta name="keywords" content="Tromay, casa de cambio, casa de cambio La Paz, casa de cambio Bolivia, mejor casa de cambio, tipo de cambio dólar Bolivia, cambio de divisas, dólar hoy Bolivia, euro, Kapitalya, ecosistema fintech, forex Bolivia">
    <meta property="og:title" content="Tromay — La casa de cambio física #1 de Bolivia">
    <meta property="og:description" content="@yield('description', 'Más de 30 años cambiando divisas en La Paz con las mejores tasas del mercado. Tasas en vivo y el ecosistema fintech Kapitalya detrás.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Tromay Casa de Cambio">
    <meta property="og:locale" content="es_BO">
    <meta property="og:image" content="{{ url('assets/images/index/exchange.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tromay — La casa de cambio física #1 de Bolivia">
    <meta name="twitter:description" content="@yield('description', 'Más de 30 años cambiando divisas en La Paz con las mejores tasas del mercado. Tasas en vivo desde forex.')">
    <meta name="twitter:image" content="{{ url('assets/images/index/exchange.jpg') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- ⬡ Structured data — JSON-LD para posicionar como casa de cambio física líder --}}
    @php
        $kapBase = rtrim(url('/'), '/');
        $kapLd = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'              => 'FinancialService',
                    '@id'                => $kapBase . '/#business',
                    'name'               => 'Tromay Casa de Cambio',
                    'alternateName'      => 'Kapitalya Servicios Integrales',
                    'description'        => 'Casa de cambio física líder en La Paz, Bolivia, con más de 30 años de trayectoria. Compra y venta de dólar, euro y monedas de la región a las mejores tasas del mercado, con tasas en vivo desde Forex ERP.',
                    'url'                => $kapBase,
                    'telephone'          => '+591-64082967',
                    'email'              => 'kapitalyabolivia@gmail.com',
                    'image'              => $kapBase . '/assets/images/index/exchange.jpg',
                    'currenciesAccepted' => 'USD, EUR, CLP, PEN, BRL, ARS, BOB',
                    'priceRange'         => '$$',
                    'slogan'             => 'La casa de cambio física número uno de Bolivia',
                    'areaServed'         => ['@type' => 'City', 'name' => 'La Paz, Bolivia'],
                    'address'            => [
                        '@type'           => 'PostalAddress',
                        'streetAddress'   => 'Av. Las Delicias Nro. 207-C, Zona Villa Fátima',
                        'addressLocality' => 'La Paz',
                        'addressRegion'   => 'La Paz',
                        'addressCountry'  => 'BO',
                    ],
                    'geo' => ['@type' => 'GeoCoordinates', 'latitude' => -16.484, 'longitude' => -68.130],
                    'openingHoursSpecification' => [
                        [
                            '@type'     => 'OpeningHoursSpecification',
                            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                            'opens'     => '08:00', 'closes' => '19:00',
                        ],
                        [
                            '@type'     => 'OpeningHoursSpecification',
                            'dayOfWeek' => 'Sunday', 'opens' => '08:00', 'closes' => '13:00',
                        ],
                    ],
                    'sameAs' => ['https://kapitalya.com.bo'],
                ],
                [
                    '@type'      => 'WebSite',
                    '@id'        => $kapBase . '/#website',
                    'url'        => $kapBase,
                    'name'       => 'Tromay Casa de Cambio',
                    'inLanguage' => 'es-BO',
                    'publisher'  => ['@id' => $kapBase . '/#business'],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($kapLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts — Kapitalya Typography System -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Base template styles -->
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}">
    {{-- owl.carousel + magnific-popup CSS eliminados: sus clases no se usan en ninguna vista --}}
    <link rel="stylesheet" href="{{ url('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/odometer.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/responsive.css') }}">

    <!-- ⬡ Tromay canonical tokens, then Kapitalya component overrides -->
    <link rel="stylesheet" href="{{ url('assets/css/tromay-tokens.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/kapitalya.css') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ url('assets/images/kapitalya-icon.svg') }}">

    <title>Tromay — @yield('title', 'Casa de Cambio | La Paz, Bolivia')</title>
</head>

<body>

<a href="#main-content" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden" onfocus="this.style.cssText='position:fixed;top:8px;left:8px;width:auto;height:auto;padding:8px 16px;background:#1a1f3a;color:#fff;border-radius:4px;font-size:14px;font-weight:600;z-index:9999;text-decoration:none;outline:2px solid #f59e0b'" onblur="this.style.cssText='position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden'">Ir al contenido principal</a>

<!-- Toast container -->
<div id="kap-toast-container" style="position:fixed;bottom:16px;right:16px;z-index:9999;display:flex;flex-direction:column;gap:8px;" role="region" aria-live="polite" aria-label="Notificaciones"></div>
<style>
.kap-toast{display:flex;align-items:center;gap:12px;padding:12px 16px;border-radius:8px;color:#fff;min-width:260px;max-width:380px;box-shadow:0 4px 12px rgba(0,0,0,.2);animation:kap-ti .2s ease;font-size:14px;font-weight:500}
.kap-toast-success{background:#10b981}.kap-toast-error{background:#ef4444}.kap-toast-warning{background:#f59e0b}.kap-toast-info{background:#3b82f6}
.kap-toast-close{background:none;border:none;color:#fff;cursor:pointer;opacity:.7;padding:2px;font-size:16px;line-height:1;margin-left:auto}
@keyframes kap-ti{from{opacity:0;transform:translateX(16px)}to{opacity:1;transform:none}}
</style>
<script>
window.kapToast={
  _icons:{success:'✓',error:'✕',warning:'⚠',info:'ℹ'},
  show(msg,type,dur=4000){
    const c=document.getElementById('kap-toast-container');if(!c)return;
    const t=document.createElement('div');t.className=`kap-toast kap-toast-${type||'info'}`;t.setAttribute('role','alert');
    t.innerHTML=`<span style="font-weight:700;flex-shrink:0">${this._icons[type||'info']}</span><span style="flex:1">${msg}</span><button class="kap-toast-close" aria-label="Cerrar">✕</button>`;
    t.querySelector('.kap-toast-close').onclick=()=>t.remove();
    c.appendChild(t);setTimeout(()=>{t.style.transition='.3s ease';t.style.opacity='0';t.style.transform='translateX(16px)';setTimeout(()=>t.remove(),300)},dur);
  },
  success(m,d){this.show(m,'success',d)},
  error(m,d){this.show(m,'error',d)},
  warning(m,d){this.show(m,'warning',d)},
  info(m,d){this.show(m,'info',d)},
};
</script>

    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
            <div class="indicator">
                <svg width="16px" height="12px">
                    <polyline id="back"  points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                    <polyline id="front" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                </svg>
            </div>
        </div>
    </div>

    <!-- ================================================================
         HEADER
    ================================================================ -->
    <header class="header-area">

        <!-- Top bar: contact + live rate pill -->
        <div class="top-header top-header-four">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-sm-6">
                        <ul class="header-left-content">
                            <li>
                                <i class="bx bx-phone-call"></i>
                                <a href="tel:+591-64082967">+591 64082967</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-7 col-sm-6 text-end">
                        @if(isset($dollar) && $dollar)
                        <span class="kap-rate-pill">
                            <span class="pill-code">USD</span>
                            <span style="color:var(--kap-text-muted);font-size:10px">C</span>
                            <span class="pill-buy" data-tick-buy="{{ $dollar->id }}">{{ number_format($dollar->buy, 4) }}</span>
                            <span style="color:var(--kap-text-muted);font-size:10px">V</span>
                            <span class="pill-sell" data-tick-sell="{{ $dollar->id }}">{{ number_format($dollar->sell, 4) }}</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main navigation -->
        <div class="navbar-area navbar-area-four">
            <div class="mobile-nav">
                <div class="container">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ url('assets/images/kapitalya-wordmark-light.svg') }}" alt="Kapitalya" class="kap-logo-for-dark" style="height:38px;width:auto;">
                        <img src="{{ url('assets/images/kapitalya-wordmark-dark.svg') }}" alt="Kapitalya" class="kap-logo-for-light" style="height:38px;width:auto;">
                    </a>
                </div>
            </div>

            <div class="main-nav">
                <div class="container">
                    <nav class="navbar navbar-expand-md">
                        <a class="navbar-brand" href="{{ route('home') }}">
                            <img src="{{ url('assets/images/kapitalya-wordmark-light.svg') }}" alt="Kapitalya" class="kap-logo-for-dark" style="height:42px;width:auto;">
                            <img src="{{ url('assets/images/kapitalya-wordmark-dark.svg') }}" alt="Kapitalya" class="kap-logo-for-light" style="height:42px;width:auto;">
                        </a>

                        <div class="collapse navbar-collapse mean-menu">
                            <ul class="navbar-nav m-auto">
                                <li class="nav-item">
                                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                        Inicio
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                                        Nosotros
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('dolar-hoy') }}" class="nav-link {{ request()->routeIs('dolar-hoy') ? 'active' : '' }}">
                                        Dólar hoy
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('home') }}#servicios" class="nav-link">
                                        Servicios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('home') }}#ecosistema" class="nav-link">
                                        Ecosistema
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('quote') }}" class="nav-link {{ request()->routeIs('quote') ? 'active' : '' }}">
                                        Cotizaciones
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                                        Contacto
                                    </a>
                                </li>
                            </ul>

                            <div class="others-option" style="display:flex;align-items:center;gap:8px;">
                                <a href="https://kapitalya.com.bo" class="kap-hub-link" title="Ecosistema Kapitalya" aria-label="Ir al Hub Kapitalya" style="color:var(--kap-text-muted,rgba(255,255,255,0.6));font-size:0.82rem;text-decoration:none;padding:5px 12px;border:1px solid rgba(255,255,255,0.15);border-radius:6px;white-space:nowrap;transition:all 0.15s;">
                                    ← Hub
                                </a>
                                <div class="get-quote">
                                    <a href="{{ route('quote') }}" class="default-btn">
                                        <span>Simular Operación</span>
                                    </a>
                                </div>
                                <button class="kap-theme-toggle" id="kap-theme-toggle" aria-label="Cambiar tema" title="Modo claro / oscuro">
                                    <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                    </svg>
                                    <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="5"/>
                                        <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                        <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Responsive dot menu -->
            <div class="others-option-for-responsive">
                <div class="container">
                    <div class="dot-menu">
                        <div class="inner">
                            <div class="circle circle-one"></div>
                            <div class="circle circle-two"></div>
                            <div class="circle circle-three"></div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="option-inner">
                            <div class="others-option justify-content-center d-flex align-items-center">
                                <div class="get-quote">
                                    <a href="{{ route('quote') }}" class="default-btn">
                                        <span>Simular Operación</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- END HEADER -->

    <main id="main-content" tabindex="-1">
    @yield('body')
    </main>

    <!-- ⬡ Bloque de anuncio (AdSense) — solo si está configurado y con consentimiento -->
    @if(config('services.adsense.client') && config('services.adsense.slot'))
    <div class="container" style="max-width:970px;margin:24px auto 8px;">
        <div style="text-align:center;font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:var(--kap-text-muted,#8a90a6);margin-bottom:6px;">Publicidad</div>
        <ins class="adsbygoogle kap-ad-slot"
             style="display:block;min-height:90px;"
             data-ad-client="{{ config('services.adsense.client') }}"
             data-ad-slot="{{ config('services.adsense.slot') }}"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
    @endif

    <!-- ================================================================
         FOOTER
    ================================================================ -->
    <footer class="footer-area pt-100 pb-70">
        <div class="container">
            <div class="row">

                <!-- Brand column -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <div class="kap-footer-brand">
                            <img src="{{ url('assets/images/kapitalya-wordmark-light.svg') }}" alt="Kapitalya" class="kap-logo-for-dark" style="height:44px;width:auto;">
                            <img src="{{ url('assets/images/kapitalya-wordmark-dark.svg') }}" alt="Kapitalya" class="kap-logo-for-light" style="height:44px;width:auto;">
                        </div>
                        <p class="kap-footer-tagline">
                            Más de 30 años evolucionando hacia el futuro.<br>
                            Servicios comerciales, financieros, tecnológicos y administrativos para Bolivia.
                        </p>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Contacto</h3>
                        <ul class="address">
                            <li class="location">
                                <i class="bx bxs-location-plus"></i>
                                Av. Las Delicias Nro. 207-C, Zona Villa Fátima, La Paz
                            </li>
                            <li>
                                <i class="bx bxs-envelope"></i>
                                <a href="mailto:kapitalyabolivia@gmail.com">kapitalyabolivia@gmail.com</a>
                            </li>
                            <li>
                                <i class="bx bxs-phone-call"></i>
                                <a href="tel:+5912218312">+591 2218312</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Currencies -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Divisas</h3>
                        <ul class="import-link">
                            @foreach($cashes as $cash)
                                <li>
                                    <a href="{{ route('dinero.show', $cash->id) }}">
                                        {{ $cash->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Navegación</h3>
                        <ul class="import-link">
                            <li><a href="{{ route('about') }}">Acerca de Kapitalya</a></li>
                            <li><a href="{{ route('home') }}#servicios">Nuestros Servicios</a></li>
                            <li><a href="{{ route('quote') }}">Simulador de Tasas</a></li>
                            <li><a href="{{ route('dolar-hoy') }}">Dólar hoy en Bolivia</a></li>
                            <li><a href="{{ route('seo.convertidor', ['par' => 'usd-bob']) }}">Convertidor USD / BOB</a></li>
                            <li><a href="{{ route('contact') }}">Contacto</a></li>
                            <li><a href="{{ route('privacy') }}">Política de Privacidad</a></li>
                            <li><a href="{{ route('terms') }}">Términos de Uso</a></li>
                            <li><a href="{{ env('PORTFOLIO_URL', 'https://cv.kapitalya.com.bo') }}" target="_blank" rel="noopener">Portafolio del Autor</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <!-- Copyright -->
    <div class="copy-right-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <p>
                        &copy; {{ date('Y') }} Kapitalya Servicios Integrales. La Paz, Bolivia.
                    </p>
                </div>
                <div class="col-lg-6 col-md-6">
                    <ul class="footer-menu">
                        <li><a href="{{ route('privacy') }}">Privacidad</a></li>
                        <li><a href="{{ route('terms') }}">Términos</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Go top -->
    <div class="go-top">
        <i class="bx bx-chevrons-up"></i>
        <i class="bx bx-chevrons-up"></i>
    </div>

    <!-- Floating contact buttons: WhatsApp + Telegram -->
    <div class="kap-float-btns">
        <a href="https://api.whatsapp.com/send?phone=59164082967&text=Hola%20Kapitalya%2C%20necesito%20informaci%C3%B3n"
           class="kap-float-btn kap-float-wsp"
           target="_blank" rel="noopener noreferrer"
           title="WhatsApp · +591 64082967">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.557 4.118 1.531 5.845L.057 23.945l6.272-1.648A11.934 11.934 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.794 9.794 0 01-5.031-1.388l-.361-.214-3.722.977.995-3.634-.235-.374A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
            </svg>
        </a>
        <a href="https://t.me/+59164082967"
           class="kap-float-btn kap-float-tg"
           target="_blank" rel="noopener noreferrer"
           title="Telegram · +591 64082967">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="22" height="22">
                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-2.012 9.483c-.148.664-.543.826-1.099.513l-3.048-2.245-1.47 1.415c-.163.163-.3.3-.614.3l.219-3.103 5.645-5.098c.245-.218-.054-.34-.38-.121L6.67 14.063 3.67 13.13c-.658-.206-.671-.658.138-.974l10.94-4.218c.547-.197 1.027.133.814.31z"/>
            </svg>
        </a>
    </div>

    <!-- ================================================================
         SCRIPTS
    ================================================================ -->
    <script defer src="{{ url('assets/js/jquery.min.js') }}"></script>
    <script defer src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script defer src="{{ url('assets/js/meanmenu.min.js') }}"></script>
    <script defer src="{{ url('assets/js/nice-select.min.js') }}"></script>
    <script defer src="{{ url('assets/js/jarallax.min.js') }}"></script>
    <script defer src="{{ url('assets/js/appear.min.js') }}"></script>
    <script defer src="{{ url('assets/js/odometer.min.js') }}"></script>
    <script defer src="{{ url('assets/js/smoothscroll.min.js') }}"></script>
    <script defer src="{{ url('assets/js/wow.min.js') }}"></script>
    <script defer src="{{ url('assets/js/form-validator.min.js') }}"></script>
    <script defer src="{{ url('assets/js/custom.js') }}"></script>
    <!-- ⬡ Kapitalya Platform (defer: mantiene el orden, no bloquea el parse) -->
    <script defer src="{{ url('assets/js/kapitalya.js') }}"></script>

    @yield('scripts')

    <!-- ⬡ OfflineBanner -->
    <div id="kap-offline" style="display:none;position:fixed;top:0;left:0;right:0;z-index:9998;background:#f59e0b;color:#fff;text-align:center;padding:8px 16px;font-size:13px;font-weight:600;" role="alert">
      ⚠️ Sin conexión a internet — los datos pueden no estar actualizados
    </div>
    <script>
    (function(){
      function sync(){document.getElementById('kap-offline').style.display=navigator.onLine?'none':'block'}
      sync();window.addEventListener('online',sync);window.addEventListener('offline',sync);
    })();
    </script>

    <!-- ⬡ Theme toggle -->
    <script>
    (function(){
        var btn = document.getElementById('kap-theme-toggle');
        if (!btn) return;
        btn.addEventListener('click', function(){
            var html = document.documentElement;
            var next = html.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('kap-theme', next);
        });
    })();
    </script>

    {{-- ⬡ Seguimiento de conversiones — no-op salvo que GA esté activo (tras consentimiento) --}}
    <script>
    (function(){
        function track(name, params){ if (typeof window.gtag === 'function'){ try { window.gtag('event', name, params || {}); } catch(e){} } }
        document.addEventListener('click', function(e){
            var a = e.target.closest ? e.target.closest('a,button') : null; if (!a) return;
            var href = (a.getAttribute && a.getAttribute('href')) || '';
            if (a.classList.contains('kap-float-wsp') || /wa\.me|api\.whatsapp\.com/.test(href)) track('contact_whatsapp', {transport_type:'whatsapp'});
            else if (a.classList.contains('kap-float-tg') || /t\.me/.test(href)) track('contact_telegram', {transport_type:'telegram'});
            else if (a.classList.contains('default-btn') || /\/quote\b/.test(href)) track('cta_simular_operacion');
        }, {passive:true});
    })();
    </script>

    {{-- ⬡ Consentimiento de cookies + monetización (anuncios AdSense / analítica Google) --}}
    @php
        $kapAdsClient = config('services.adsense.client');
        $kapGaId      = config('services.analytics.ga_id');
        $kapMonetize  = $kapAdsClient || $kapGaId;
    @endphp
    @if($kapMonetize)
    <div id="kap-cookie-banner" role="dialog" aria-live="polite" aria-label="Aviso de cookies"
         style="display:none;position:fixed;left:16px;right:16px;bottom:16px;z-index:9997;max-width:520px;margin:0 auto;background:#12172e;color:#e6e8f0;border:1px solid rgba(255,255,255,.12);border-radius:14px;box-shadow:0 10px 40px rgba(0,0,0,.45);padding:18px 20px;font-size:13.5px;line-height:1.55;">
        <div style="display:flex;align-items:center;gap:8px;font-weight:700;margin-bottom:6px;">
            <span aria-hidden="true">🍪</span> Cookies y privacidad
        </div>
        <p style="margin:0 0 12px;color:#b9bed4;">
            Usamos cookies técnicas para el funcionamiento del sitio y, con tu permiso,
            cookies de publicidad y medición de Google para mantener este servicio gratuito.
            Podés cambiar tu elección cuando quieras. Consultá la
            <a href="{{ route('privacy') }}" style="color:#f59e0b;text-decoration:underline;">Política de Privacidad</a>.
        </p>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button id="kap-consent-accept" type="button"
                    style="flex:1;min-width:130px;cursor:pointer;border:0;border-radius:9px;padding:10px 14px;font-weight:700;font-size:13.5px;color:#0c1024;background:#f59e0b;">Aceptar todo</button>
            <button id="kap-consent-reject" type="button"
                    style="flex:1;min-width:130px;cursor:pointer;border:1px solid rgba(255,255,255,.2);border-radius:9px;padding:10px 14px;font-weight:600;font-size:13.5px;color:#e6e8f0;background:transparent;">Solo esenciales</button>
        </div>
    </div>
    <script>
    (function(){
        var KEY='kap-consent-v1';
        var banner=document.getElementById('kap-cookie-banner');
        var adsClient={!! json_encode($kapAdsClient ?: '') !!};
        var gaId={!! json_encode($kapGaId ?: '') !!};
        function get(){try{return localStorage.getItem(KEY)}catch(e){return null}}
        function set(v){try{localStorage.setItem(KEY,v)}catch(e){}}
        function loadAds(){
            if(!adsClient||window.__kapAds)return;window.__kapAds=1;
            var s=document.createElement('script');s.async=true;s.crossOrigin='anonymous';
            s.src='https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client='+encodeURIComponent(adsClient);
            document.head.appendChild(s);
            document.querySelectorAll('ins.adsbygoogle.kap-ad-slot').forEach(function(){
                try{(window.adsbygoogle=window.adsbygoogle||[]).push({});}catch(e){}
            });
        }
        function loadGa(){
            if(!gaId||window.__kapGa)return;window.__kapGa=1;
            var s=document.createElement('script');s.async=true;
            s.src='https://www.googletagmanager.com/gtag/js?id='+encodeURIComponent(gaId);
            document.head.appendChild(s);
            window.dataLayer=window.dataLayer||[];
            window.gtag=function(){window.dataLayer.push(arguments)};
            window.gtag('js',new Date());
            window.gtag('config',gaId,{anonymize_ip:true});
        }
        function enable(){
            // Consentimiento real: con permiso, los anuncios pueden ser
            // personalizados (el head arranca en requestNonPersonalizedAds=1
            // mientras no haya consentimiento; ver loader en <head>).
            try{(window.adsbygoogle=window.adsbygoogle||[]).requestNonPersonalizedAds=0;}catch(e){}
            loadAds();loadGa();
        }
        var c=get();
        if(c==='granted'){enable();}
        else if(c!=='denied'&&banner){banner.style.display='block';}
        var a=document.getElementById('kap-consent-accept'),r=document.getElementById('kap-consent-reject');
        if(a)a.onclick=function(){set('granted');if(banner)banner.style.display='none';enable();};
        if(r)r.onclick=function(){set('denied');if(banner)banner.style.display='none';};
    })();
    </script>
    @endif
</body>
</html>
