<!doctype html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        {{-- "{{ url('') }}" --}}
		<!-- Links of CSS files -->
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

		<!-- Favicon -->
		<link rel="icon" type="image/png" href="{{ url('assets/img/favicon.png') }}">
		<!-- Title -->
		<title>Tromay - Casa de Cambios</title>
    </head>

    <body>
		<!-- Start Preloader Area -->
		<div class="preloader">
            <div class="loader">
                <div class="loader-outter"></div>
                <div class="loader-inner"></div>

                <div class="indicator">
                    <svg width="16px" height="12px">
                        <polyline id="back" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                        <polyline id="front" points="1 6 4 6 6 11 10 1 12 6 15 6"></polyline>
                    </svg>
                </div>
            </div>
        </div>
		<!-- End Preloader Area -->

		<!-- Start Header Area -->
		<header class="header-area">
			<!-- Start Top Header -->
			<div class="top-header top-header-four">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-lg-6 col-sm-6">
							<ul class="header-left-content">
								<li>
									<i class="bx bx-phone-call"></i>
									<a href="tel:+591-78939071">+591-78939071</a>
								</li>
							</ul>
						</div>

						<div class="col-lg-6 col-sm-6">
							<ul class="header-right-content">
								<li>
									<a href="{{ url('') }}" target="_blank">
										<i class="bx bxl-facebook"></i>
									</a>
								</li>
								{{-- <li>
									<a href="{{ url('') }}" target="_blank">
										<i class="bx bxl-instagram"></i>
									</a>
								</li>
								<li>
									<a href="{{ url('') }}" target="_blank">
										<i class="bx bxl-linkedin"></i>
									</a>
								</li>
								<li>
									<a href="{{ url('') }}" target="_blank">
										<i class="bx bxl-twitter"></i>
									</a>
								</li> --}}
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- Start Top Header -->

			<!-- Start Nav Area -->
			<div class="navbar-area navbar-area-four">
				<div class="mobile-nav">
					<div class="container">
						<a href="{{ url('/') }}" class="logo">
							<script type="text/javascript" style="display:none">
                            //<![CDATA[
                            window.__mirage2 = {petok:"ib5wGw2lIsIXjsmAaMgGEUF8N4._2ykkEywDvhV6Wb4-1800-0"};
                            //]]>
                            </script>
                            {{-- <script type="text/javascript" src="{{('../../../ajax.cloudflare.com/cdn-cgi/scripts/04b3eb47/cloudflare-static/mirage2.min.js') }}"></script> --}}
                            <img src="{{ url('assets/img/logo.png') }}" alt="Logo"></noscript>
						</a>
					</div>
				</div>

				<div class="main-nav">
					<div class="container">
						<nav class="navbar navbar-expand-md">
							<a class="navbar-brand" href="{{ url('/') }}">
								<img src="{{ url('assets/img/logo.png') }}" alt="Logo"></noscript>
							</a>

							<div class="collapse navbar-collapse mean-menu">
								<ul class="navbar-nav m-auto">
									<li class="nav-item">
										<a href="{{ url('/') }}" class="nav-link">Inicio</a>
									</li>

                                    <li class="nav-item">
										<a href="{{ url('about') }}" class="nav-link">Nosotros</a>
									</li>

									<li class="nav-item">
										<a href="{{ url('quote') }}" class="nav-link">Cotizaciones</a>
									</li>

									<li class="nav-item">
										<a href="{{ url('contact') }}" class="nav-link">Contactanos</a>
									</li>
								</ul>

								<div class="others-option">
									<div class="get-quote">
										<a href="{{ url('quote') }}" class="default-btn">
											<span>Tener una cotizacion</span>
										</a>
									</div>
                                    <div class="get-quote">
                                        @foreach ($dollars as $dolar)
                                        <a class="default-btn">
											<span>Dolar Venta: {{$dolar->sell}} Compra: {{$dolar->buy}}</span>
										</a>
                                        @endforeach
									</div>
                                    {{-- <div class="get-quote">
										<a href="{{ url('') }}" class="default-btn">
											<span>Euro: Venta: {{$euro->sell}} Compra: {{$euro->buy}}</span>
										</a>
									</div> --}}
								</div>
							</div>
						</nav>
					</div>
				</div>

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
										<a href="{{ url('') }}" class="default-btn">
											<span>Tener una cotizacion</span>
										</a>
									</div>

									{{-- <div class="cart-icon">
										<a href="{{ url('') }}">
											<i class="bx bx-cart"></i>
											<span>0</span>
										</a>
									</div> --}}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Nav Area -->
		</header>
		<!-- End Header Area -->


        @yield('body')



		<!-- Start Footer Area -->
		<footer class="footer-area pt-100 pb-70 jarallax" data-jarallax='{"speed": 0.3}'>
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-6">
						<div class="single-footer-widget">
							<a href="{{ url('/') }}" class="logo">
								<img src="{{ url('assets/img/logo.png') }}" alt="Image"></noscript>
							</a>

                            <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d239.12006879739513!2d-68.1210681!3d-16.4795323!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbo!4v1668615366092!5m2!1sen!2sbo" width="70%" height="60%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
							{{-- <ul class="social-icon">
								<li>
									<a href="{{ url('') }}">
										<i class="bx bxl-facebook"></i>
									</a>
								</li>
								<li>
									<a href="{{ url('') }}">
										<i class="bx bxl-instagram"></i>
									</a>
								</li>
								<li>
									<a href="{{ url('') }}">
										<i class="bx bxl-linkedin-square"></i>
									</a>
								</li>
								<li>
									<a href="{{ url('') }}">
										<i class="bx bxl-twitter"></i>
									</a>
								</li>
							</ul> --}}
						</div>
					</div>

					<div class="col-lg-3 col-md-6">
						<div class="single-footer-widget">
							<h3>Direccion</h3>

							<ul class="address">
								<li class="location">
									<i class="bx bxs-location-plus"></i>
									137 Av. Las Americas Zona Villa Fatima
								</li>
								<li>
									<i class="bx bxs-envelope"></i>
									{{-- <a href="{{ url('https://templates.envytheme.com/cdn-cgi/l/email-protection#f69e939a9a99b6859387828fd895999b') }}"><span class="__cf_email__" data-cfemail="0e666b6262614e7d6b7f7a77206d6163">[email&#160;protected]</span></a> --}}

                                    <a href="{{ url('mailto: tromay@gmail.com') }}">tromay@gmail.com</a>
								</li>
								<li>
									<i class="bx bxs-phone-call"></i>
									<a href="{{ url('tel:+1-(514)-312-5678') }}">+591 2218312</a>
								</li>
							</ul>
						</div>
					</div>

					<div class="col-lg-3 col-md-6">
						<div class="single-footer-widget">
							<h3>Monedas</h3>

							<ul class="import-link">
                                @foreach ($cashes as $cash)
                                @if(($cash->status)==1)
                                <li>
									<a href="{{ route('dinero.show',$cash->id) }}">{{$cash->name}}</a>
								</li>
                                @endif
                                @endforeach

							</ul>
						</div>
					</div>

					<div class="col-lg-3 col-md-6">
						<div class="single-footer-widget">
							<h3>Recursos</h3>

							<ul class="import-link">
								<li>
									<a href="{{ url('about') }}">Acerca de Nosotros</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!-- End Footer Area -->

		<!-- Start Copy Right Area -->
		<div class="copy-right-area">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-6 col-md-6">
						<p>
							Copyright <i class="bx bx-copyright"></i>2022 Tromay. Desarrollado por
							<a href="{{ url('https://serguicv-production.up.railway.app/') }}" target="_blank">Serius69</a>
						</p>
					</div>

					<div class="col-lg-6 col-md-6">
						<ul class="footer-menu">
							<li>
								<a href="{{ url('privacy') }}" >
									Politica de Privacidad
								</a>
							</li>
							<li>
								<a href="{{ url('terms') }}" >
									Terminos y condiciones
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- End Copy Right Area -->

		<!-- Start Go Top Area -->
		<div class="go-top">
			<i class="bx bx-chevrons-up"></i>
			<i class="bx bx-chevrons-up"></i>
		</div>
		<!-- End Go Top Area -->


       <!-- Links of JS files -->
        <script data-cfasync="false" src="{{ url('../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js') }}"></script>
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
		<script src="{{ url('assets/js/form-validator.min.js') }}"></script>
		<script src="{{ url('assets/js/ajaxchimp.min.js') }}"></script>
        <script src="{{ url('assets/js/wow.min.js') }}"></script>
		<script src="{{ url('assets/js/custom.js') }}"></script>
    </body>
</html>
