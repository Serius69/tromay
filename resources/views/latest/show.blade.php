@extends('layout.master')
@section('title', e($latest->name) . ' — Insights Financieros')
@section('description', e(Str::limit(strip_tags($latest->description ?? ''), 155) ?: 'Análisis y novedades del mercado cambiario boliviano por Tromay, la casa de cambio física #1 de La Paz.'))

@section('body')

{{-- Page header --}}
<div class="kap-detail-header">
    <div class="container">
        <div class="kap-detail-label">Insights Financieros</div>
        <h1 class="kap-detail-h1">{{ e($latest->name) }}</h1>
        <div class="kap-article-meta">
            <span class="kap-article-meta-item">
                <i class="bx bx-user"></i>
                {{ e($latest->author) }}
            </span>
            <span class="kap-article-meta-item">
                <i class="bx bx-calendar"></i>
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
                         class="kap-article-img"
                         onerror="this.onerror=null;this.src='{{ url('assets/images/kapitalya-tromay-banner-1500x500.svg') }}'">
                    @endif

                    <div class="kap-article-body">
                        {!! nl2br(e($latest->description)) !!}
                    </div>

                    @if($latest->url)
                    <div class="kap-article-source">
                        <a href="{{ e($latest->url) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="kap-sim-btn d-inline-flex align-items-center gap-2 text-decoration-none"
                           style="width:auto;padding:12px 24px;">
                            Fuente original <i class="bx bx-link-external"></i>
                        </a>
                    </div>
                    @endif
                </div>

                <a href="{{ route('home') }}" class="kap-article-back">
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
                        {{-- Badge honesto según rate_source (ver seo/dolar-hoy). --}}
                        @if(($dollar->rate_source ?? null) === 'forex')
                        <span class="kap-live-badge">En vivo</span>
                        @elseif(($dollar->rate_source ?? null) === 'cache')
                        <span class="kap-live-badge kap-live-badge--cache">Caché</span>
                        @else
                        <span class="kap-live-badge kap-live-badge--cache">Referencial</span>
                        @endif
                    </div>
                    @foreach($cashes->take(4) as $cash)
                    <div class="kap-rate-row" data-kap-id="{{ $cash->id }}">
                        <span class="kap-rc-code">{{ e($cash->name) }}</span>
                        <div class="kap-rv">
                            <div class="kap-rv-item">
                                <div class="lbl">C</div>
                                <div class="val buy" data-kap-buy>{{ number_format($cash->buy, 4) }}</div>
                            </div>
                            <div class="kap-rv-item">
                                <div class="lbl">V</div>
                                <div class="val sell" data-kap-sell>{{ number_format($cash->sell, 4) }}</div>
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
                    <div class="kap-related-item">
                        <a href="{{ route('noticia.show', $item->id) }}" class="kap-related-link">
                            {{ e($item->name) }}
                        </a>
                        <span class="kap-related-date">
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
