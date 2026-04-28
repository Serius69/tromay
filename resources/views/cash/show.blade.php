@extends('layout.master')
@section('title', e($cash->name) . ' — Tasas de Cambio')

@section('body')

{{-- Page header --}}
<div class="page-title-area" style="background:var(--kap-surface);border-bottom:1px solid var(--kap-border);padding:60px 0 40px;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:var(--kap-green);font-weight:700;margin-bottom:8px;">
                    Detalle de divisa
                </div>
                <h1 style="font-family:var(--font-display);font-size:clamp(28px,4vw,42px);font-weight:800;letter-spacing:-.02em;margin:0;">
                    {{ e($cash->name) }}
                </h1>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background:transparent;gap:4px;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" style="color:var(--kap-text-sec);">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('quote') }}" style="color:var(--kap-text-sec);">Cotizaciones</a>
                    </li>
                    <li class="breadcrumb-item active" style="color:var(--kap-text);">{{ e($cash->name) }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section style="padding:70px 0;background:var(--kap-black);">
    <div class="container">
        <div class="row g-4">

            {{-- Main rate card --}}
            <div class="col-lg-4 col-md-6">
                <div class="kap-sim-card h-100">
                    <div class="text-center mb-24" style="padding-bottom:20px;border-bottom:1px solid var(--kap-border);">
                        <img src="{{ url('assets/img/cash/' . $cash->path) }}"
                             alt="{{ e($cash->name) }}"
                             style="width:80px;height:80px;object-fit:cover;border-radius:12px;border:1px solid var(--kap-border);margin-bottom:16px;">
                        <h3 style="font-size:22px!important;margin-bottom:4px!important;">{{ e($cash->name) }}</h3>
                        <span class="kap-live-badge">Actualizado</span>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-6">
                            <div style="background:var(--kap-black);border:1px solid var(--kap-border);border-radius:10px;padding:16px;text-align:center;">
                                <div style="font-size:10px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:6px;">Compra</div>
                                <div style="font-size:24px;font-weight:800;font-family:var(--font-mono);color:var(--kap-green);">
                                    {{ number_format($cash->buy, 4) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div style="background:var(--kap-black);border:1px solid var(--kap-border);border-radius:10px;padding:16px;text-align:center;">
                                <div style="font-size:10px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:6px;">Venta</div>
                                <div style="font-size:24px;font-weight:800;font-family:var(--font-mono);color:var(--kap-gold);">
                                    {{ number_format($cash->sell, 4) }}
                                </div>
                            </div>
                        </div>
                        @if($cash->oficial)
                        <div class="col-12">
                            <div style="background:var(--kap-black);border:1px solid var(--kap-border);border-radius:10px;padding:14px;text-align:center;">
                                <div style="font-size:10px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:4px;">Tasa Oficial</div>
                                <div style="font-size:20px;font-weight:700;font-family:var(--font-mono);color:var(--kap-text-sec);">
                                    {{ number_format($cash->oficial, 4) }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('quote') }}" class="kap-sim-btn d-block text-center text-decoration-none mt-4"
                       style="color:var(--kap-black)!important;">
                        Simular operación
                    </a>
                </div>
            </div>

            {{-- Mini simulator --}}
            <div class="col-lg-4 col-md-6">
                <div class="kap-sim-card h-100">
                    <h3 style="font-size:18px!important;margin-bottom:6px!important;">Calculadora rápida</h3>
                    <p style="font-size:13px!important;margin-bottom:20px!important;">Estimá el resultado de tu operación</p>

                    <form id="kap-simulator" novalidate>
                        @csrf
                        <div class="kap-tabs" style="margin-bottom:20px;">
                            <button type="button" class="kap-tab-btn active" data-type="buy">Compro</button>
                            <button type="button" class="kap-tab-btn" data-type="sell">Vendo</button>
                        </div>

                        <input type="hidden" id="sim-currency" value="{{ $cash->id }}">

                        <div class="kap-form-group">
                            <label for="sim-amount" class="kap-label">Monto (BOB)</label>
                            <input type="number" id="sim-amount" class="kap-input"
                                   placeholder="Ej: 500" min="1" step="0.01" autocomplete="off">
                        </div>

                        <div id="sim-result" style="display:none;"></div>

                        {{-- Embed rates for JS --}}
                        <script>
                            window.__kapRates = window.__kapRates || [];
                            window.__kapRates.push({
                                id:   {{ $cash->id }},
                                name: @json($cash->name),
                                buy:  {{ $cash->buy }},
                                sell: {{ $cash->sell }},
                            });
                        </script>
                    </form>
                </div>
            </div>

            {{-- Other currencies --}}
            <div class="col-lg-4">
                <div class="kap-sim-card h-100">
                    <h3 style="font-size:18px!important;margin-bottom:16px!important;">Otras divisas</h3>
                    @foreach($cashes->where('id', '!=', $cash->id)->take(6) as $other)
                    <div class="kap-rate-row" data-kap-id="{{ $other->id }}">
                        <div class="kap-rc">
                            <div class="kap-rc-flag" style="width:28px;height:28px;font-size:16px;">
                                <img src="{{ url('assets/img/cash/' . $other->path) }}"
                                     alt="{{ e($other->name) }}"
                                     onerror="this.style.display='none';this.parentElement.textContent='💱'">
                            </div>
                            <span class="kap-rc-code" style="font-size:13px;">{{ e($other->name) }}</span>
                        </div>
                        <div class="kap-rv">
                            <div class="kap-rv-item">
                                <div class="lbl">C</div>
                                <div class="val buy" style="font-size:13px;" data-kap-buy>{{ number_format($other->buy, 4) }}</div>
                            </div>
                            <div class="kap-rv-item">
                                <div class="lbl">V</div>
                                <div class="val sell" style="font-size:13px;" data-kap-sell>{{ number_format($other->sell, 4) }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <a href="{{ route('quote') }}" class="read-more mt-3 d-inline-flex" style="font-size:13px;">
                        Ver todas las divisas <i class="flaticon-right-arrow"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
