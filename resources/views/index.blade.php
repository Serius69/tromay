@extends('layout.master')

@section('body')
<!-- Start Banner Area -->
<section class="banner-area banner-area-four bg-4 jarallax" data-jarallax='{"speed": 0.3}'>
    <div class="d-table">
        <div class="d-table-cell">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-9">
                        <div class="banner-content">
                            <span class="top-title wow fadeInDown" data-wow-delay="1s">Bolivia</span>
                            <h1 class="wow fadeInDown" data-wow-delay="1s">TROMAY</h1>
                            <p class="wow fadeInLeft" data-wow-delay="1s">Casa de cambios dedicada a la atencion al cliente en general.</p>

                            <div class="banner-btn wow fadeInUp" data-wow-delay="1s">
                                <a href="{{ url('') }}" class="default-btn">
                                    <span>Contactanos</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="banner-video wow zoomIn" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: zoomIn;">
                            <a href="{{ url('') }}" class="video-btn popup-youtube">
                                <i class="bx bx-play"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!-- Start Feature Area -->
<section class="feature-area feature-area-four pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-testing"></i>
                            <h3>Atencion Rapida</h3>
                        </div>
                        <p>En nuestras sucursales lograra la rapida atencion en el cambio de moneda extranjera.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-cybercrime"></i>
                            <h3>Confiabilidad</h3>
                        </div>
                        <p>Realizamos todas nuestras transacciones de manera transparente.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 offset-sm-3 offset-lg-0">
                <div class="single-feature overly-one">
                    <div class="overly-two">
                        <div class="title">
                            <i class="flaticon-cyber-security"></i>
                            <h3>+20 anios de trayectoria</h3>
                        </div>
                        <p>Tenemos mas de 20 anios en el mercado y la experiencia de ello en cada una de las atenciones.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Feature Area -->

<!-- Start Our Approach Area -->
{{-- <section class="our-approach-area our-approach-area-four pb-70">
    <div class="container">
        <div class="section-title">
            <span>cambio de moneda</span>
            <h2>Que monedas realizamos cambio</h2>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="approach-img">
                    <img data-cfsrc="assets/img/approach-img-4.jpg" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/approach-img-4.jpg" alt="Image"></noscript>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="approach-content">
                    <h3>Monedas extranjeras que puedes cambiar en una de nuestras sucursales.</h3>

                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-approach">
                                <h3>Dolar Estadounidense</h3>
                                <p>Es la moneda de curso legal de Estados Unidos.</p>
                                <p>Tipo de Cambio:</p><p></p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="single-approach">
                                <h3>Euro</h3>
                                <p>Es la moneda de curso legal de Estados Unidos.</p>
                                <p>Tipo de Cambio:</p><p></p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="single-approach">
                                <h3>Real</h3>
                                <p>Es la moneda de curso legal de Estados Unidos.</p>
                                <p>Tipo de Cambio:</p><p></p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="single-approach">
                                <h3>Operational Security</h3>
                                <p>Es la moneda de curso legal de Estados Unidos.</p>
                                <p>Tipo de Cambio:</p><p></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- End Our Approach Area -->

<!-- Start Our Challenges Area -->
{{-- <section class="challenges-area pt-100 pb-70 jarallax" data-jarallax='{"speed": 0.3}'>
    <div class="container">
        <div class="section-title white-title">
            <span>Our Challenges</span>
            <h2>You Can Protect Your Organization’s Cybersecurity By Us</h2>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="single-challenges overly-one">
                    <div class="overly-two">
                        <i class="flaticon-threat"></i>
                        <h3>Identifying Threats</h3>
                        <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
                        <span class="flaticon-threat"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="single-challenges overly-one">
                    <div class="overly-two">
                        <i class="flaticon-cyber"></i>
                        <h3>Cyber Risk Assessment</h3>
                        <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
                        <span class="flaticon-cyber"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="single-challenges overly-one">
                    <div class="overly-two">
                        <i class="flaticon-cyber-security-1"></i>
                        <h3>Testing Cyber Security</h3>
                        <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
                        <span class="flaticon-cyber-security-1"></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="single-challenges overly-one">
                    <div class="overly-two">
                        <i class="flaticon-password"></i>
                        <h3>Managing Cloud Security</h3>
                        <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
                        <span class="flaticon-password"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- End Our Challenges Area -->

<!-- Start Services Area -->
<section class="services-area pt-100 pb-70">
    <div class="container">
        <div class="section-title">
            <span>Cybersecurity Services</span>
            <h2>Monedas extranjeras que puedes cambiar en una de nuestras sucursales.</h2>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="single-services">
                    <div class="services-img">
                        <a href="{{ url('') }}">
                            <img src="{{ url('assets\img\cash\dolar.jpg') }}" alt="Image" ></noscript>
                        </a>
                    </div>

                    <div class="services-content">
                        <h3><a href="{{ url('') }}">Dolar Estadounidense</a></h3>
                        <div class="content">
                            <p>Tipo de Cambio</p>

                            <a href="{{ url('') }}" class="read-more">
                                Saber Mas
                                <i class="flaticon-right-arrow"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Services Area -->

<!-- Start Solution Area -->
{{-- <section class="solution-area pb-70">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="solution-content">
                    <div class="solution-title">
                        <span>Manera de atencion</span>
                        <h2></h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-6">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3>
                                        <a href="{{ url('') }}">
                                            Product Consultation
                                        </a>
                                    </h3>
                                    <p>Acerca a una .</p>
                                    <span>01</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3>
                                        <a href="{{ url('') }}">
                                            Security Consultation
                                        </a>
                                    </h3>
                                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.</p>
                                    <span>02</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 offset-md-3 offset-lg-0">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3>
                                        <a href="{{ url('') }}">
                                            24/7 Technical Support
                                        </a>
                                    </h3>
                                    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.</p>
                                    <span>03</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 pr-0">
                <div class="solution-img">
                    <img data-cfsrc="{{ url('') }}" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="{{ url('') }}" alt="Image"></noscript>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- End Solution Area -->

<!-- Start Protect Area -->
<section class="protect-area protect-area-four pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="protect-img">
                    <img src="{{ url('assets\img\index\cambio.jpg') }}" alt="Image">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="protect-content">
                    <div class="protect-title">
                        <span>Cybersecurity Protect</span>
                        <h2>Recomendaciones al realizar cualquier transaccion</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-database"></i>
                                    <h3>Revisa tu cambio</h3>
                                    <p>La empr.</p>
                                    <span class="flaticon-database"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-application"></i>
                                    <h3>Database Security</h3>
                                    <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
                                    <span class="flaticon-application"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-security"></i>
                                    <h3>Web Security</h3>
                                    <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
                                    <span class="flaticon-security"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-security-1"></i>
                                    <h3>Server Security</h3>
                                    <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
                                    <span class="flaticon-security-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Eed Protect Area -->

<!-- Start Testimonials Area -->
{{-- <section class="testimonials-area ptb-100 jarallax" data-jarallax='{"speed": 0.3}'>
    <div class="container">
        <div class="testimonials">
            <span>What Our Customers Say</span>

            <div class="testimonials-slider owl-carousel owl-theme">
                <div class="testimonials-item">
                    <i class="flaticon-quote"></i>
                    <p>“Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.”</p>

                    <ul>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                    </ul>

                    <h3>Jastin Anderson</h3>
                    <span>CEO</span>
                </div>
                <div class="testimonials-item">
                    <i class="flaticon-quote"></i>
                    <p>“Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.”</p>

                    <ul>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                        <li>
                            <i class="bx bxs-star"></i>
                        </li>
                    </ul>

                    <h3>Juhon Anderson</h3>
                    <span>Manager</span>
                </div>
            </div>
        </div>
    </div>
</section> --}}
<!-- Eed Testimonials Area -->

<!-- Start Partner Area -->
<div class="partner-area ptb-100">
    <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
            <div class="partner-item">
                <a href="{{ url('') }}">
                    <img data-cfsrc="{{ url('') }}" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="{{ url('') }}" alt="Image"></noscript>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Partner Area -->

<!-- Start Blog Area -->
<section class="blog-area blog-area-four pb-70">
    <div class="container">
        <div class="section-title">
            <span>Ultimas noticias financieras</span>
            <h2>Algunos articulos de la situacion de dferentes divisas en el mundo</h2>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single-blog">
                    <div class="blog-img">
                        <a href="{{ url('') }}">
                            <img src="{{ url('') }}" alt="Image"></noscript>
                        </a>
                    </div>

                    <div class="blog-content">
                        <span>January 20, 2021</span>
                        <h3><a href="{{ url('') }}">Secure The Network</a></h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

                        <a href="{{ url('') }}" class="read-more">
                            Saber Mas
                            <i class="flaticon-right-arrow"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Blog Area -->
@endsection
