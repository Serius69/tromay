<!doctype html>
<html lang="es" data-theme="dark">
<head>
    <script>
        (function(){var t=localStorage.getItem('kap-theme');if(t==='light')document.documentElement.setAttribute('data-theme','light');}());
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Kapitalya Servicios Integrales — Empresa boliviana con más de 30 años de trayectoria. Servicios comerciales, financieros, tecnológicos y administrativos para personas, emprendedores y microempresas en La Paz, Bolivia.">
    <meta name="keywords" content="Kapitalya, servicios integrales, Bolivia, La Paz, recargas, giros nacionales, consultoría, tecnología, emprendedores, microempresas, casa de cambios, divisas">
    <meta property="og:title" content="Kapitalya Servicios Integrales — Bolivia">
    <meta property="og:description" content="Más de 30 años evolucionando hacia el futuro. Servicios comerciales, financieros, tecnológicos y administrativos en La Paz, Bolivia.">
    <meta property="og:type" content="website">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts — Kapitalya Typography System -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Base template styles -->
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/nice-select.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/odometer.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/responsive.css') }}">

    <!-- ⬡ Kapitalya Brand System — always last to override -->
    <link rel="stylesheet" href="{{ url('assets/css/kapitalya.css') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ url('assets/images/kapitalya-icon.svg') }}">

    <title>Kapitalya — @yield('title', 'Servicios Integrales | La Paz, Bolivia')</title>
</head>

<body>
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
                                    <a href="{{ route('home') }}#servicios" class="nav-link">
                                        Servicios
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

    @yield('body')

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
                                137 Av. Las Americas, Zona Villa Fátima, La Paz
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
                            <li><a href="{{ route('contact') }}">Contacto</a></li>
                            <li><a href="{{ route('privacy') }}">Política de Privacidad</a></li>
                            <li><a href="{{ route('terms') }}">Términos de Uso</a></li>
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
    <script src="{{ url('assets/js/jquery.min.js') }}"></script>
    <script src="{{ url('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/js/meanmenu.min.js') }}"></script>
    <script src="{{ url('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ url('assets/js/nice-select.min.js') }}"></script>
    <script src="{{ url('assets/js/magnific-popup.min.js') }}"></script>
    <script src="{{ url('assets/js/jarallax.min.js') }}"></script>
    <script src="{{ url('assets/js/appear.min.js') }}"></script>
    <script src="{{ url('assets/js/odometer.min.js') }}"></script>
    <script src="{{ url('assets/js/smoothscroll.min.js') }}"></script>
    <script src="{{ url('assets/js/wow.min.js') }}"></script>
    <script src="{{ url('assets/js/custom.js') }}"></script>
    <!-- ⬡ Kapitalya Platform -->
    <script src="{{ url('assets/js/kapitalya.js') }}"></script>

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
</body>
</html>
