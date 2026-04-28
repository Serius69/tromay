@extends('layout.master')
@section('title', 'Cotizaciones — Tasas en Tiempo Real')

@section('body')

{{-- Page header --}}
<div style="background:var(--kap-surface);border-bottom:1px solid var(--kap-border);padding:70px 0 50px;">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-7">
                <span style="display:inline-block;font-size:11px;text-transform:uppercase;letter-spacing:.12em;
                             color:var(--kap-green);font-weight:700;
                             background:var(--kap-green-dim);border:1px solid rgba(0,200,150,.2);
                             border-radius:100px;padding:5px 12px;margin-bottom:14px;">
                    Plataforma Kapitalya
                </span>
                <h1 style="font-family:var(--font-display);font-size:clamp(32px,5vw,56px);
                           font-weight:800;letter-spacing:-.03em;margin-bottom:12px;">
                    Tasas <span style="color:var(--kap-green)">en tiempo real</span>
                </h1>
                <p style="font-size:16px;color:var(--kap-text-sec);max-width:520px;line-height:1.7;margin:0;">
                    Consultá las cotizaciones actualizadas de todas las divisas disponibles y simulá tu operación antes de acercarte a sucursal.
                </p>
            </div>
            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <span class="kap-live-badge" style="font-size:13px;">Actualización automática cada 60 seg</span>
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
                <div style="background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:var(--radius);">
                    {{-- Table header --}}
                    <div style="display:grid;grid-template-columns:1fr auto auto auto;gap:16px;
                                padding:12px 20px;border-bottom:1px solid var(--kap-border);">
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);">Divisa</span>
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-green);text-align:right;min-width:80px;">Compra</span>
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-gold);text-align:right;min-width:80px;">Venta</span>
                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);text-align:right;min-width:80px;">Oficial</span>
                    </div>

                    @forelse($cashes as $cash)
                    <a href="{{ route('dinero.show', $cash->id) }}"
                       style="display:grid;grid-template-columns:1fr auto auto auto;gap:16px;
                              padding:16px 20px;border-bottom:1px solid var(--kap-border);
                              text-decoration:none;transition:background .2s;cursor:pointer;"
                       data-kap-id="{{ $cash->id }}"
                       onmouseover="this.style.background='rgba(255,255,255,0.025)'"
                       onmouseout="this.style.background='transparent'">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:36px;height:36px;border-radius:8px;overflow:hidden;
                                        border:1px solid var(--kap-border);background:var(--kap-surface-2);
                                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <img src="{{ url('assets/img/cash/' . $cash->path) }}"
                                     alt="{{ e($cash->name) }}"
                                     style="width:100%;height:100%;object-fit:cover;"
                                     onerror="this.style.display='none';this.parentElement.textContent='💱'">
                            </div>
                            <span style="font-size:15px;font-weight:700;font-family:var(--font-display);color:var(--kap-text);">
                                {{ e($cash->name) }}
                            </span>
                        </div>
                        <span style="font-size:16px;font-weight:700;font-family:var(--font-mono);
                                     color:var(--kap-green);text-align:right;min-width:80px;"
                              data-kap-buy>
                            {{ number_format($cash->buy, 4) }}
                        </span>
                        <span style="font-size:16px;font-weight:700;font-family:var(--font-mono);
                                     color:var(--kap-gold);text-align:right;min-width:80px;"
                              data-kap-sell>
                            {{ number_format($cash->sell, 4) }}
                        </span>
                        <span style="font-size:15px;font-weight:600;font-family:var(--font-mono);
                                     color:var(--kap-text-muted);text-align:right;min-width:80px;">
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
                    <h3 style="font-size:18px!important;margin-bottom:6px!important;">Simulador</h3>
                    <p style="font-size:13px!important;color:var(--kap-text-sec)!important;margin-bottom:22px!important;">
                        Calculá el resultado estimado de tu operación
                    </p>

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

                    <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--kap-border);">
                        <p style="font-size:12px!important;color:var(--kap-text-muted)!important;margin-bottom:12px!important;">
                            ¿Querés operar? Acercate a nuestra sucursal.
                        </p>
                        <a href="{{ route('contact') }}"
                           style="display:block;text-align:center;padding:12px;
                                  border:1px solid var(--kap-border);border-radius:8px;
                                  font-size:13px;font-weight:600;color:var(--kap-text-sec)!important;
                                  text-decoration:none;transition:all .25s;"
                           onmouseover="this.style.borderColor='var(--kap-green)';this.style.color='var(--kap-green)'"
                           onmouseout="this.style.borderColor='var(--kap-border)';this.style.color='var(--kap-text-sec)'">
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
