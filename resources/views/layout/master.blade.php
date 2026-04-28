<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Kapitalya — Plataforma financiera inteligente. Optimización de capital, tasas en tiempo real y análisis de mercado.">

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
    <link rel="icon" type="image/png" href="{{ url('assets/img/favicon.png') }}">

    <title>Kapitalya — @yield('title', 'Plataforma Financiera Inteligente')</title>
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
                                <a href="tel:+591-78939071">+591 78939071</a>
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
                        <img src="{{ url('assets/img/logo.png') }}" alt="Kapitalya">
                    </a>
                </div>
            </div>

            <div class="main-nav">
                <div class="container">
                    <nav class="navbar navbar-expand-md">
                        <a class="navbar-brand" href="{{ route('home') }}">
                            <img src="{{ url('assets/img/logo.png') }}" alt="Kapitalya">
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

                            <div class="others-option">
                                <div class="get-quote">
                                    <a href="{{ route('quote') }}" class="default-btn">
                                        <span>Simular Operación</span>
                                    </a>
                                </div>
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
                            <img src="{{ url('assets/img/logo.png') }}" alt="Kapitalya" style="height:32px;">
                        </div>
                        <p class="kap-footer-tagline">
                            Plataforma de optimización financiera inteligente.<br>
                            Decisiones basadas en datos, control de capital en tiempo real.
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
                                <a href="mailto:contacto@kapitalya.com">contacto@kapitalya.com</a>
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

                <!-- Resources -->
                <div class="col-lg-3 col-md-6">
                    <div class="single-footer-widget">
                        <h3>Plataforma</h3>
                        <ul class="import-link">
                            <li><a href="{{ route('about') }}">Acerca de Kapitalya</a></li>
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
                        &copy; {{ date('Y') }} Kapitalya. Plataforma financiera inteligente.
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

    <!-- WhatsApp -->
    <a href="https://api.whatsapp.com/send?phone=59178939071" class="btn-wsp" target="_blank" rel="noopener noreferrer">
        <i class="fa fa-whatsapp icono"></i>
    </a>

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
</body>
</html>
