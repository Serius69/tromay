@extends('layout.master')
@section('title', 'Cotizaciones — Tasas en Tiempo Real')

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

                {{-- Rate table --}}
                <div class="kap-rate-table-wrap">
                    {{-- Table header --}}
                    <div class="kap-rate-table-head">
                        <span class="kap-rate-col">Divisa</span>
                        <span class="kap-rate-col kap-rate-col--right kap-rate-col--green">Compra</span>
                        <span class="kap-rate-col kap-rate-col--right kap-rate-col--gold">Venta</span>
                        <span class="kap-rate-col kap-rate-col--right">Oficial</span>
                    </div>

                    @php
                    $qFlagMap=['usd'=>'estados unidos.png','eur'=>'union europea.png','clp'=>'chile.png','pen'=>'peru.png','brl'=>'brazil.png','ars'=>'argentina.png'];
                    @endphp
                    @forelse($cashes as $cash)
                    @php $qFlag = $qFlagMap[strtolower($cash->name)] ?? null; @endphp
                    <a href="{{ route('dinero.show', $cash->id) }}"
                       class="kap-rate-table-row"
                       data-kap-id="{{ $cash->id }}">
                        <div class="kap-flag-cell">
                            <div class="kap-flag-box">
                                @if($qFlag)
                                <img src="{{ url('assets/images/' . $qFlag) }}"
                                     alt="{{ e($cash->name) }}">
                                @else
                                <span style="font-size:18px;">💱</span>
                                @endif
                            </div>
                            <span class="kap-flag-name">{{ e($cash->name) }}</span>
                        </div>
                        <span class="kap-rate-val kap-rate-val--green" data-kap-buy>
                            {{ number_format($cash->buy, 4) }}
                        </span>
                        <span class="kap-rate-val kap-rate-val--gold" data-kap-sell>
                            {{ number_format($cash->sell, 4) }}
                        </span>
                        <span class="kap-rate-val kap-rate-val--muted">
                            {{ $cash->oficial ? number_format($cash->oficial, 4) : '—' }}
                        </span>
                    </a>
                    @empty
                    <div style="padding:40px;text-align:center;color:var(--kap-text-muted);">
                        No hay divisas disponibles en este momento.
                    </div>
                    @endforelse
                </div>

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
                            <label for="sim-amount" class="kap-label">Monto a entregar (BOB)</label>
                            <input type="number" id="sim-amount" class="kap-input"
                                   placeholder="Ej: 1000" min="1" step="0.01" autocomplete="off">
                        </div>

                        <div id="sim-result" style="display:none;"></div>
                    </form>

                    <div class="kap-card-footer">
                        <p style="font-size:12px!important;color:var(--kap-text-muted)!important;margin-bottom:12px!important;">
                            ¿Querés operar? Acercate a nuestra sucursal.
                        </p>
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
