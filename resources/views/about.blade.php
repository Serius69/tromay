@extends('layout.master')
@section('body')
<div class="page-title-area page-title-style-two">
    <div class="container">
        <div class="page-title-content">
            <h2>Nosotros</h2>
            <ul>
                <li>
                    <a href="{{ url('') }}">
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
                        <img src="{{ url('assets/img/cash/dolar.jpg') }}" alt="Image" width="500" height="500">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-content">
                    <div class="about-title">
                        <span>Caracteristicas</span>
                        <h2>Caracteristicas</h2>
                    </div>

                    <div class="tab">
                        <ul class="tabs">
                            <li>
                                Nuestra Experiencia
                            </li>
                            <li>
                                Por que nosotros?
                            </li>

                        </ul>

                        <div class="tab_content">
                            <div class="tabs_item">
                                <p>Contamos con un alto recorrido en tiempo en el mercado.</p>

                                <ul>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Mas de 30 anios atendiendo al publico en general.
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Product Security
                                    </li>
                                </ul>
                            </div>

                            <div class="tabs_item">
                                <p>Confiabilidad a la hora de realizar transacciones.</p>

                                <ul>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Transacciones seguras.
                                    </li>
                                    <li>
                                        <i class="bx bx-check-circle"></i>
                                        Manejo correcto de las divisas.
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

<!-- Start About Area -->
<section class="solution-area pb-70">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="solution-content">
                    <div class="solution-title">
                        <span>Mision y Vision</span>
                        <h2></h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3>
                                        <a href="{{ url('') }}">
                                            Historia
                                        </a>
                                    </h3>
                                    <p>Desde nuestra fundación en 1999, Tromay, empresa de cambio de moneda extranjera, ha superado varios hitos históricos en el mercado cambiario. Utilizando esa experiencia, nos hemos ganado la confianza de una amplia gama de clientes.</p>
                                    <span>Historia</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3>
                                        <a href="{{ url('') }}">
                                            Mision
                                        </a>
                                    </h3>
                                    <p>  Proveer atencion de calidad en el cambio de moneda extranjera.</p>
                                    <span>Mision</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6 offset-md-3 offset-lg-0">
                            <div class="single-solution overly-one">
                                <div class="overly-two">
                                    <h3>
                                        <a href="{{ url('') }}">
                                            Vision
                                        </a>
                                    </h3>
                                    <p>Ser una empresa lider en el cambio de moneda extranjera.</p>
                                    <span>Vision</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 pr-0">
                <div class="solution-img">
                    <img src="{{ url('') }}" alt="Image"></noscript>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End About Area -->




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
