@extends('layout.master')
@section('title', 'Página no encontrada')
@section('description', 'La página que buscás no existe. Consultá las tasas de cambio de hoy de Tromay Casa de Cambio o volvé al inicio.')

@section('body')
<div class="page-title-area page-title-style-two">
    <div class="container">
        <div class="page-title-content">
            <h2>Página no encontrada</h2>
            <ul>
                <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
                <li class="active">Error 404</li>
            </ul>
        </div>
    </div>
</div>

<div class="error-area ptb-100">
    <div class="d-table">
        <div class="d-table-cell">
            <div class="error-content">
                {{-- Cifra decorativa: el encabezado real de la página es el <h1> de abajo. --}}
                <p class="error-code" aria-hidden="true">
                    <span class="a">4</span>
                    <span class="red">0</span>
                    <span class="b">4</span>
                </p>
                <h1>No encontramos esta página</h1>
                <p>
                    El enlace puede estar roto o la página pudo haberse movido.
                    Podés volver al inicio o mirar
                    <a href="{{ route('quote') }}">las tasas de cambio de hoy</a>.
                </p>
                <a href="{{ url('/') }}" class="default-btn two">
                    <span>Volver al inicio</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
