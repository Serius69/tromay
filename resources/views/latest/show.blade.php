@extends('layout.master')
@section('title', e($latest->name) . ' — Insights Financieros')

@section('body')

{{-- Page header --}}
<div style="background:var(--kap-surface);border-bottom:1px solid var(--kap-border);padding:60px 0 40px;">
    <div class="container">
        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:var(--kap-green);font-weight:700;margin-bottom:12px;">
            Insights Financieros
        </div>
        <h1 style="font-family:var(--font-display);font-size:clamp(26px,4vw,44px);font-weight:800;letter-spacing:-.025em;margin-bottom:16px;">
            {{ e($latest->name) }}
        </h1>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <span style="font-size:13px;color:var(--kap-text-muted);">
                <i class="bx bx-user" style="color:var(--kap-green);margin-right:4px;"></i>
                {{ e($latest->author) }}
            </span>
            <span style="font-size:13px;color:var(--kap-text-muted);">
                <i class="bx bx-calendar" style="color:var(--kap-green);margin-right:4px;"></i>
                {{ \Carbon\Carbon::parse($latest->date_publication)->translatedFormat('d \d\e F, Y') }}
            </span>
        </div>
    </div>
</div>

<section style="padding:70px 0;background:var(--kap-black);">
    <div class="container">
        <div class="row g-5">

            {{-- Article body --}}
            <div class="col-lg-8">
                <div class="kap-sim-card">
                    @if($latest->path)
                    <img src="{{ url('assets/img/latest/' . $latest->path) }}"
                         alt="{{ e($latest->name) }}"
                         style="width:100%;border-radius:10px;margin-bottom:28px;object-fit:cover;max-height:400px;">
                    @endif

                    <div style="font-size:16px;line-height:1.85;color:var(--kap-text-sec);">
                        {!! nl2br(e($latest->description)) !!}
                    </div>

                    @if($latest->url)
                    <div style="margin-top:28px;padding-top:20px;border-top:1px solid var(--kap-border);">
                        <a href="{{ e($latest->url) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="kap-sim-btn d-inline-flex align-items-center gap-2 text-decoration-none"
                           style="width:auto;padding:12px 24px;color:var(--kap-black)!important;">
                            Fuente original <i class="bx bx-link-external"></i>
                        </a>
                    </div>
                    @endif
                </div>

                <a href="{{ route('home') }}"
                   style="display:inline-flex;align-items:center;gap:6px;margin-top:24px;color:var(--kap-text-sec);font-size:14px;text-decoration:none;transition:color .25s;">
                    <i class="bx bx-left-arrow-alt"></i> Volver al inicio
                </a>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                {{-- Live rates widget --}}
                @if(isset($dollar) && $dollar)
                <div class="kap-sim-card mb-4">
                    <div class="kap-panel-head mb-3">
                        <h4 style="font-size:13px!important;">Tasas del día</h4>
                        <span class="kap-live-badge">En vivo</span>
                    </div>
                    @foreach($cashes->take(4) as $cash)
                    <div class="kap-rate-row" data-kap-id="{{ $cash->id }}" style="padding:10px 0;">
                        <span class="kap-rc-code" style="font-size:13px;color:var(--kap-text)!important;">{{ e($cash->name) }}</span>
                        <div class="kap-rv" style="gap:12px;">
                            <div class="kap-rv-item">
                                <div class="lbl">C</div>
                                <div class="val buy" style="font-size:12px;" data-kap-buy>{{ number_format($cash->buy, 4) }}</div>
                            </div>
                            <div class="kap-rv-item">
                                <div class="lbl">V</div>
                                <div class="val sell" style="font-size:12px;" data-kap-sell>{{ number_format($cash->sell, 4) }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <a href="{{ route('quote') }}" class="read-more mt-2 d-inline-flex" style="font-size:12px;">
                        Simular operación <i class="flaticon-right-arrow"></i>
                    </a>
                </div>
                @endif

                {{-- Related articles --}}
                @if($related->count())
                <div class="kap-sim-card">
                    <h4 style="font-size:14px!important;font-weight:700!important;margin-bottom:16px!important;
                               padding-bottom:12px!important;border-bottom:1px solid var(--kap-border)!important;">
                        Más insights
                    </h4>
                    @foreach($related as $item)
                    <div style="padding:12px 0;border-bottom:1px solid var(--kap-border);">
                        <a href="{{ route('noticia.show', $item->id) }}"
                           style="font-size:14px;font-weight:600;color:var(--kap-text)!important;
                                  text-decoration:none;line-height:1.4;display:block;margin-bottom:4px;
                                  transition:color .25s;">
                            {{ e($item->name) }}
                        </a>
                        <span style="font-size:11px;color:var(--kap-text-muted);">
                            {{ \Carbon\Carbon::parse($item->date_publication)->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection
