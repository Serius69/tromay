@extends('layout.master')
@section('title', 'Cotizaciones — Tasas en Tiempo Real')
@section('description', 'Simulá tu cambio de divisas en Bolivia: calculá cuánto recibís por tus dólares o euros con las tasas en vivo de Tromay antes de operar en sucursal.')

@section('body')

{{-- Page header --}}
<div class="kap-page-header">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-7">
                <span class="kap-page-label">Plataforma Kapitalya</span>
                <h1 class="kap-page-h1">
                    Tasas <span class="kap-hl-green">en tiempo real</span>
                </h1>
                <p class="kap-page-sub">
                    Consultá las cotizaciones actualizadas de todas las divisas disponibles y simulá tu operación antes de acercarte a sucursal.
                </p>
            </div>
            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <span class="kap-live-badge" style="font-size:13px;">Actualización automática cada 60 seg</span>
                <span data-kap-timestamp class="kap-timestamp">Actualizado ahora mismo</span>
                <br class="d-none d-lg-block">
                <span id="kap-open-status" class="kap-status-badge kap-status-badge--open" style="margin-top:10px;display:inline-flex;">
                    Abierto ahora
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Ticker bar --}}
@if($cashes->count())
<div class="kap-ticker-bar">
    <div class="kap-ticker-track">
        @foreach($cashes as $cash)
        <span class="kap-ticker-item">
            <span class="t-code">{{ Str::upper(Str::limit($cash->name, 3, '')) }}</span>
            <span class="t-sep">/</span>
            <span style="font-size:10px;color:var(--kap-text-muted)">C</span>
            <span class="t-buy" data-tick-buy="{{ $cash->id }}">{{ number_format($cash->buy, 4) }}</span>
            <span style="font-size:10px;color:var(--kap-text-muted)">V</span>
            <span class="t-sell" data-tick-sell="{{ $cash->id }}">{{ number_format($cash->sell, 4) }}</span>
        </span>
        @endforeach
    </div>
</div>
@endif

<section style="padding:70px 0 100px;background:var(--kap-black);">
    <div class="container">
        <div class="row g-4">

            {{-- LEFT: currencies grid --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h2 style="font-family:var(--font-display);font-size:20px;font-weight:700;margin:0;">
                        Divisas disponibles
                        <span style="font-size:14px;font-weight:500;color:var(--kap-text-muted);margin-left:8px;">
                            ({{ $cashes->count() }})
                        </span>
                    </h2>
                    <span style="font-size:12px;color:var(--kap-text-muted);">
                        <i class="bx bx-time-five" style="color:var(--kap-green);"></i>
                        Hoy {{ now()->format('H:i') }}
                    </span>
                </div>

                {{-- Rate table — tabla REAL (no un grid de divs): es la tabla de
                     tasas de la página de conversión, y con lectores de pantalla
                     un div-grid no permite asociar "6,91" con su fila ni con su
                     columna. Las filas siguen siendo CSS grid (ver kapitalya.css),
                     así que la maqueta no cambia. --}}
                <table class="kap-rate-table-wrap">
                    <caption class="visually-hidden">
                        Tasas de cambio de hoy, expresadas en bolivianos (BOB) por unidad de divisa.
                    </caption>
                    <thead>
                        <tr class="kap-rate-table-head">
                            <th scope="col" class="kap-rate-col">Divisa</th>
                            <th scope="col" class="kap-rate-col kap-rate-col--right kap-rate-col--green">Compra</th>
                            <th scope="col" class="kap-rate-col kap-rate-col--right kap-rate-col--gold">Venta</th>
                            <th scope="col" class="kap-rate-col kap-rate-col--right">Oficial</th>
                        </tr>
                    </thead>
                    <tbody>

                    @php
                    $qFlagMap=['usd'=>'estados unidos.png','eur'=>'union europea.png','clp'=>'chile.png','pen'=>'peru.png','brl'=>'brazil.png','ars'=>'argentina.png'];
                    @endphp
                    @forelse($cashes as $cash)
                    @php $qFlag = $qFlagMap[strtolower($cash->name)] ?? null; @endphp
                    <tr class="kap-rate-table-row" data-kap-id="{{ $cash->id }}">
                        <th scope="row" class="kap-flag-cell">
                            <div class="kap-flag-box">
                                @if($qFlag)
                                <img src="{{ url('assets/images/' . $qFlag) }}"
                                     alt="" width="36" height="36" loading="lazy" decoding="async">
                                @else
                                <span style="font-size:18px;" aria-hidden="true">💱</span>
                                @endif
                            </div>
                            {{-- El enlace se estira sobre toda la fila (::after) para
                                 conservar el clic completo del diseño original, pero
                                 el foco de teclado recae en un único enlace con
                                 nombre accesible propio. --}}
                            <a href="{{ route('dinero.show', $cash->id) }}" class="kap-rate-row-link">
                                <span class="kap-flag-name">{{ e($cash->name) }}</span>
                                <span class="visually-hidden">— ver detalle y evolución</span>
                            </a>
                        </th>
                        <td class="kap-rate-val kap-rate-val--green" data-kap-buy>
                            {{ number_format($cash->buy, 4) }}
                        </td>
                        <td class="kap-rate-val kap-rate-val--gold" data-kap-sell>
                            {{ number_format($cash->sell, 4) }}
                        </td>
                        <td class="kap-rate-val kap-rate-val--muted">
                            {{ $cash->oficial ? number_format($cash->oficial, 4) : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding:40px;text-align:center;color:var(--kap-text-muted);">
                            No hay divisas disponibles en este momento.
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>

                <p style="font-size:12px;color:var(--kap-text-muted);margin-top:12px;">
                    * Las tasas mostradas son referenciales y pueden variar al momento de la operación.
                    Tasas expresadas en bolivianos (BOB).
                </p>
            </div>

            {{-- RIGHT: Simulator --}}
            <div class="col-lg-4">
                <div class="kap-sim-card" style="position:sticky;top:90px;">
                    <h3 class="kap-card-title">Simulador</h3>
                    <p class="kap-card-subtitle">Calculá el resultado estimado de tu operación</p>

                    <form id="kap-simulator" novalidate>
                        @csrf
                        <div class="kap-tabs">
                            <button type="button" class="kap-tab-btn active" data-type="buy">Compro</button>
                            <button type="button" class="kap-tab-btn" data-type="sell">Vendo</button>
                        </div>

                        <div class="kap-form-group">
                            <label for="sim-currency" class="kap-label">Divisa</label>
                            <select id="sim-currency" class="kap-select">
                                <option value="">— Seleccionar —</option>
                                @foreach($cashes as $cash)
                                <option value="{{ $cash->id }}"
                                        data-buy="{{ $cash->buy }}"
                                        data-sell="{{ $cash->sell }}">
                                    {{ e($cash->name) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="kap-form-group">
                            <label for="sim-amount" class="kap-label">Monto (BOB)</label>
                            <input type="number" id="sim-amount" class="kap-input"
                                   placeholder="Ej: 1000" min="1" step="0.01" autocomplete="off">
                        </div>

                        <div id="sim-result" style="display:none;"></div>
                    </form>

                    <div class="kap-card-footer">
                        <p style="font-size:12px!important;color:var(--kap-text-muted)!important;margin-bottom:12px!important;">
                            ¿Querés operar? Escribinos y cerramos tu cambio al instante.
                        </p>
                        <a href="https://api.whatsapp.com/send?phone=59164082967&text=Hola%20Tromay%2C%20quiero%20cotizar%20una%20operaci%C3%B3n%20de%20cambio"
                           target="_blank" rel="noopener noreferrer" class="default-btn"
                           style="display:flex;justify-content:center;align-items:center;gap:8px;background:#25D366;border-color:#25D366;margin-bottom:10px;">
                            <i class="bx bxl-whatsapp" style="font-size:18px;"></i>
                            <span>Cotizar por WhatsApp</span>
                        </a>
                        <a href="{{ route('contact') }}" class="kap-btn-ghost" style="justify-content:center;">
                            <i class="bx bx-map-pin" style="margin-right:4px;"></i>
                            Ver ubicación
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
