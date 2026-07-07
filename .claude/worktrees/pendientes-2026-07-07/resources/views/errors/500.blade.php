@extends('layout.master')
@section('title', 'Error del servidor')

@section('body')
<div class="page-title-area page-title-style-two">
    <div class="container">
        <div class="page-title-content">
            <h2>Error del servidor</h2>
            <ul>
                <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
                <li class="active">Error 500</li>
            </ul>
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
                    <span class="b">0</span>
                </h1>
                <h3>Error interno del servidor</h3>
                <p>
                    Algo falló de nuestro lado. Nuestro equipo ya fue notificado
                    y estamos trabajando en solucionarlo. Por favor intenta de nuevo
                    en unos minutos.
                </p>
                <a href="{{ url('/') }}" class="default-btn two">
                    <span>Volver al inicio</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
