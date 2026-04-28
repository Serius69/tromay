@extends('layout.master')
@section('title', 'Demasiadas solicitudes')

@section('body')
<div class="page-title-area page-title-style-two">
    <div class="container">
        <div class="page-title-content">
            <h2>Demasiadas solicitudes</h2>
            <ul>
                <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
                <li class="active">Error 429</li>
            </ul>
        </div>
    </div>
</div>

<div class="error-area ptb-100">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="error-content">
                <h1>
                    <span class="a">4</span>
                    <span class="red">2</span>
                    <span class="b">9</span>
                </h1>
                <h3>Calma, llegaste al límite</h3>
                <p>
                    Has realizado demasiadas solicitudes en poco tiempo.
                    @if(isset($retryAfter))
                        Intenta de nuevo en <strong>{{ $retryAfter }} segundos</strong>.
                    @else
                        Espera un momento e intenta nuevamente.
                    @endif
                </p>
                <a href="{{ url('/') }}" class="default-btn two">
                    <span>Volver al inicio</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
