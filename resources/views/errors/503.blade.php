@extends('layout.master')
@section('title', 'Mantenimiento')

@section('body')
<div class="page-title-area page-title-style-two">
    <div class="container">
        <div class="page-title-content">
            <h2>En mantenimiento</h2>
        </div>
    </div>
</div>

<div class="error-area ptb-100">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="error-content">
                <h1>
                    <span class="a">5</span>
                    <span class="red">0</span>
                    <span class="b">3</span>
                </h1>
                <h3>Actualización en curso</h3>
                <p>
                    Kapitalya está realizando una actualización programada para mejorar
                    tu experiencia. Estaremos de vuelta en pocos minutos.
                </p>
                @if(isset($retryAfter))
                <p><strong>Tiempo estimado: {{ $retryAfter }} segundos.</strong></p>
                @endif
                <a href="{{ url('/') }}" class="default-btn two">
                    <span>Intentar nuevamente</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
