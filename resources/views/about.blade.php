@extends('layout.master')
@section('body')
<div class="page-title-area">
    <div class="container">
        <div class="page-title-content">
            <h2>Nosotros</h2>
            <ul>
                <li>
                    <a href="index.html">
                        <i class="bx bx-home"></i>
                        Inicio
                    </a>
                </li>
                <li class="active">Nosotros</li>
            </ul>
        </div>
    </div>
</div>
<!-- End Page Title Area -->

<!-- Start Nosotros Area -->
<section class="about-us-area pt-100 pb-70">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-img">
                    {{-- <img data-cfsrc="{{ url('img/cash/dolar.jpg') }}" alt="Image" style="display:none;visibility:hidden;"><noscript> --}}
                        <img src="{{ url('assets/img/cash/dolar.jpg') }}" alt="Image"></noscript>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-content">
                    <div class="about-title">
                        <span>Nosotros</span>
                        <h2>Algunas de las caracteristicas de</h2>
                    </div>

                    <div class="tab">
                        <ul class="tabs">
                            <li>
                                Nuestra Experiencia
                            </li>
                            <li>
                                Por que nosotros?
                            </li>
                            <li>
                                Our Approach
                            </li>
                        </ul>

                        <div class="tab_content">
                            <div class="tabs_item">
                                <p>Contamos con un alto recorrido en tiempo en el mercado.</p>

                                <ul>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        30 anios
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Product Security
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        System Security
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Operational Security
                                    </li>
                                </ul>
                            </div>

                            <div class="tabs_item">
                                <p>.</p>

                                <ul>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Transacciones seguras
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Product Security
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        System Security
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Operational Security
                                    </li>
                                </ul>
                            </div>

                            <div class="tabs_item">
                                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Placeat atque quibusdam fuga natus non necessitatibus eveniet maiores nostrum esse ut voluptates sint dolores, voluptatum consequatur ad est enim perferendis reprehenderit.</p>

                                <ul>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Trusted Partner
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Product Security
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        System Security
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Operational Security
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Nosotros Area -->

<!-- Start Our Challenges Area -->
<section class="challenges-area pt-100 pb-70 jarallax" data-jarallax='{"speed": 0.3}'>
    <div class="container">
        <div class="section-title white-title">
            <span>Our Challenges</span>
            <h2>You can protect your organization’s cybersecurity by us</h2>
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
</section>
<!-- End Our Challenges Area -->

<!-- Start Protect Area -->
<section class="protect-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="protect-img">
                    <img data-cfsrc="assets/img/protect-img.jpg" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/protect-img.jpg" alt="Image"></noscript>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="protect-content">
                    <div class="protect-title">
                        <span>Cyber Security Protect</span>
                        <h2>Protect your website, web server, and web application for helping you being threats from the hacker</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-database"></i>
                                    <h3>Database Security</h3>
                                    <p>Lorem ipsum dolor sit amet, con sectetur adipiscing elit sed do.</p>
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
            <span>What our customers say</span>

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
<div class="partner-area bg-color ptb-70">
    <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
            <div class="partner-item">
                <a href="#">
                    <img data-cfsrc="assets/img/partner/partner-1.png" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/partner/partner-1.png" alt="Image"></noscript>
                </a>
            </div>

            <div class="partner-item">
                <a href="#">
                    <img data-cfsrc="assets/img/partner/partner-2.png" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/partner/partner-2.png" alt="Image"></noscript>
                </a>
            </div>

            <div class="partner-item">
                <a href="#">
                    <img data-cfsrc="assets/img/partner/partner-3.png" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/partner/partner-3.png" alt="Image"></noscript>
                </a>
            </div>

            <div class="partner-item">
                <a href="#">
                    <img data-cfsrc="assets/img/partner/partner-4.png" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/partner/partner-4.png" alt="Image"></noscript>
                </a>
            </div>

            <div class="partner-item">
                <a href="#">
                    <img data-cfsrc="assets/img/partner/partner-5.png" alt="Image" style="display:none;visibility:hidden;"><noscript><img src="assets/img/partner/partner-5.png" alt="Image"></noscript>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Partner Area -->
@endsection
