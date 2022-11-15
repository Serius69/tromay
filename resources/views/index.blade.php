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
                            <i class="flaticon-user"></i>
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
                            <i class="flaticon-reliability"></i>
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
                            <i class="flaticon-testing"></i>
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
<!-- Start Services Area -->
<section class="services-area pt-100 pb-70">
    <div class="container">
        <div class="section-title">
            <span>Cotizaciones divisas</span>
            <h2>Monedas extranjeras que puedes cambiar en una de nuestras sucursales.</h2>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                @foreach ($cashs as $cash )
                @if(($cash->status)==1)
                <div class="single-services">
                    <div class="services-img">
                        <a href="{{ route('dinero.show',$cash->id) }}">
                            <img src="{{ url('img/noticias/'.$cash->photo->path) }}" alt="Image" ></noscript>
                        </a>
                    </div>

                    <div class="services-content">
                        <h3><a href="{{ route('dinero.show',$cash->id) }}">{{ $cash->name }}</a></h3>
                        <div class="content">
                            <p>Compra: {{ $cash->buy }}</p>
                            <p>Venta: {{ $cash->sell }}</p>
                            <p>Oficial: {{ $cash->oficial }}</p>
                            <a href="{{ route('dinero.show',$cash->id) }}" class="read-more">
                                Saber Mas
                                <i class="flaticon-right-arrow"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End Services Area -->

<!-- Start About Area -->
<section class="solution-area pb-70">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="solution-content">
                    <div class="solution-title">
                        <span>Mision y Vision</span>
                        <h2>Acerca de Nosotros</h2>
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
                                    <span></span>
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
                        <span>Avisos</span>
                        <h2>Recomendaciones generales</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-database"></i>
                                    <h3>Horario de atencion</h3>
                                    <p>Lunes a Viernes 8:00 - 12:30 13:00 - 19:00<br>
                                    Domingos 8:00 - 12:30 13:00 - 19:00</p>
                                    <span class="flaticon-database"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="single-challenges overly-one">
                                <div class="overly-two">
                                    <i class="flaticon-application"></i>
                                    <h3>Tomar nota</h3>
                                    <p>No realizamos transacciones fuera del horario de atencion.</p>
                                    <span class="flaticon-application"></span>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-6 col-sm-6">
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
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Eed Protect Area -->
<!-- Start Partner Area -->
<div class="partner-area ptb-100">
    <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
            <div class="partner-item">
                <a href="{{ url('') }}">
                    <img src="{{ url('') }}" alt="Image"></noscript>
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
                @foreach ($latests as $latest )
                    @if(($latest->status)==1)
                <div class="single-blog">
                    <div class="blog-img">
                        <a href="{{ route('noticia.show',$latest->id) }}">
                            <img src="{{ url('img/noticias/'.$latest->photo->path) }}" alt="Image"></noscript>
                        </a>
                    </div>

                    <div class="blog-content">
                        <span>
                            <?php $mes = date('m', strtotime($latest->date_publication)); ?>
                                    @if ($mes == '01')
                                        Ene
                                    @elseif($mes == '02')
                                        Feb
                                    @elseif($mes == '03')
                                        Mar
                                    @elseif($mes == '04')
                                        Abr
                                    @elseif($mes == '05')
                                        May
                                    @elseif($mes == '06')
                                        Jun
                                    @elseif($mes == '07')
                                        Jul
                                    @elseif($mes == '08')
                                        Ago
                                    @elseif($mes == '09')
                                        Sep
                                    @elseif($mes == '10')
                                        Oct
                                    @elseif($mes == '11')
                                        Nov
                                    @elseif($mes == '12')
                                        Dic
                                    @else
                                    @endif

                        </span>
                        <h3><a href="{{ route('noticia.show',$latest->id) }}">{{ $latest->name }}</a></h3>
                        <p>{{ $latest->description }}</p>

                        <a href="{{ route('noticia.show',$latest->id) }}" class="read-more">
                            Saber Mas
                            <i class="flaticon-right-arrow"></i>
                        </a>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End Blog Area -->
@endsection
