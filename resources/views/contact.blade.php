@extends('layout.master')
@section('body')


<!-- Start Page Title Area -->
<div class="page-title-area page-title-style-two">
    <div class="container">
        <div class="page-title-content">
            <h2>Contactanos</h2>
            <ul>
                <li>
                    <a href="{{ url('') }}">
                        <i class="bx bx-home"></i>
                        Inicio
                    </a>
                </li>
                <li class="active">Contacto</li>
            </ul>
        </div>
    </div>
</div>
<!-- End Page Title Area -->

<!-- Start Contact Area -->
<section class="main-contact-area ptb-100">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="contact-wrap">

                </div>
            </div>

            <div class="col-lg-4">
                <div class="contact-info">
                    <h3>Informacion de contacto</h3>
                    <p></p>

                    <ul class="address">
                        <li class="location">
                            <i class="bx bxs-location-plus"></i>
                            <span>Direccion</span>
                            137 Avenida las Americas, Zona Villa Fatima, BO
                        </li>
                        <li>
                            <i class="bx bxs-phone-call"></i>
                            <span>Telefono</span>
                            <a href="tel:+1-(514)-312-5678">+591 2218312</a>
                            <a href="tel:+1-(514)-312-6677">+591 78939071</a>
                        </li>
                        <li>
                            <i class="bx bxs-envelope"></i>
                            <span>Correo Electronico</span>
                            <a href="">tromay@gmail.com</a>
                        </li>
                    </ul>

                    {{-- <div class="sidebar-follow-us">
                        <h3>Follow us:</h3>

                        <ul class="social-wrap">
                            <li>
                                <a href="#" target="_blank">
                                    <i class="bx bxl-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank">
                                    <i class="bx bxl-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank">
                                    <i class="bx bxl-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank">
                                    <i class="bx bxl-youtube"></i>
                                </a>
                            </li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Contact Area -->

<!-- Start Map Area -->
<div class="map-area">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d239.12006877689288!2d-68.1210681488008!3d-16.479532316606306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbo!4v1668611773954!5m2!1sen!2sbo""></iframe>
</div>
<!-- End Map Area -->
@endsection
