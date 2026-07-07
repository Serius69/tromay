@extends('layout.master')
@section('title', 'Contacto — Kapitalya Servicios Integrales')

@section('body')

{{-- ================================================================
     HERO
================================================================ --}}
<div class="kap-about-hero" style="min-height:40vh;padding:80px 0 60px;">
    <div class="kap-about-hero-grid"></div>
    <div class="kap-about-hero-glow"></div>
    <div class="container" style="position:relative;z-index:2;">
        <div class="text-center">
            <span class="kap-about-label">Estamos aquí para ayudarte</span>
            <h1 class="kap-about-h2" style="font-size:clamp(32px,4vw,52px)!important;margin-top:12px;">
                Contactá con <span class="kap-hl-green">Kapitalya</span>
            </h1>
            <p class="kap-about-hero-sub" style="margin-top:12px!important;">
                Atención personalizada para personas, emprendedores y microempresas bolivianas.
            </p>
        </div>
    </div>
</div>

{{-- ================================================================
     CONTACT CARDS + MAP
================================================================ --}}
<section style="background:var(--kap-black);padding:80px 0 90px;">
    <div class="container">
        <div class="row g-5 align-items-start">

            {{-- Info column --}}
            <div class="col-lg-5 kap-fade-up">

                {{-- Dirección --}}
                <div class="kap-contact-card">
                    <div class="kap-contact-row kap-contact-row--start">
                        <div class="kap-contact-icon kap-contact-icon--green">
                            <i class="bx bx-map-pin"></i>
                        </div>
                        <div>
                            <div class="kap-contact-label">Dirección</div>
                            <div class="kap-contact-value" style="line-height:1.5;">
                                Av. Las Delicias Nro. 207-C<br>
                                Zona Villa Fátima, La Paz, Bolivia
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Estado actual --}}
                <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px;">
                    <span id="kap-open-status" class="kap-status-badge kap-status-badge--open">
                        Abierto ahora
                    </span>
                    <span style="font-size:12px;color:var(--kap-text-muted);" id="kap-open-hours-hint"></span>
                </div>

                {{-- WhatsApp --}}
                <a href="https://api.whatsapp.com/send?phone=59164082967&text=Hola%20Kapitalya%2C%20necesito%20informaci%C3%B3n"
                   target="_blank" rel="noopener noreferrer"
                   class="kap-contact-link kap-contact-link--wsp">
                    <div class="kap-contact-row">
                        <div class="kap-contact-icon kap-contact-icon--wsp">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#25D366" width="20" height="20">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.557 4.118 1.531 5.845L.057 23.945l6.272-1.648A11.934 11.934 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.794 9.794 0 01-5.031-1.388l-.361-.214-3.722.977.995-3.634-.235-.374A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="kap-contact-label">WhatsApp</div>
                            <div class="kap-contact-value kap-contact-value--mono kap-contact-value--wsp">+591 64082967</div>
                            <div class="kap-contact-hint">Toca para abrir WhatsApp</div>
                        </div>
                    </div>
                </a>

                {{-- Telegram --}}
                <a href="https://t.me/+59164082967"
                   target="_blank" rel="noopener noreferrer"
                   class="kap-contact-link kap-contact-link--tg">
                    <div class="kap-contact-row">
                        <div class="kap-contact-icon kap-contact-icon--tg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0088cc" width="20" height="20">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-2.012 9.483c-.148.664-.543.826-1.099.513l-3.048-2.245-1.47 1.415c-.163.163-.3.3-.614.3l.219-3.103 5.645-5.098c.245-.218-.054-.34-.38-.121L6.67 14.063 3.67 13.13c-.658-.206-.671-.658.138-.974l10.94-4.218c.547-.197 1.027.133.814.31z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="kap-contact-label">Telegram</div>
                            <div class="kap-contact-value kap-contact-value--mono kap-contact-value--tg">+591 64082967</div>
                            <div class="kap-contact-hint">Toca para abrir Telegram</div>
                        </div>
                    </div>
                </a>

                {{-- Teléfono fijo --}}
                <div class="kap-contact-card">
                    <div class="kap-contact-row">
                        <div class="kap-contact-icon kap-contact-icon--green">
                            <i class="bx bx-phone"></i>
                        </div>
                        <div>
                            <div class="kap-contact-label">Teléfono</div>
                            <div class="kap-contact-value kap-contact-value--mono">+591 2218312</div>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div class="kap-contact-card">
                    <div class="kap-contact-row">
                        <div class="kap-contact-icon kap-contact-icon--gold">
                            <i class="bx bx-envelope"></i>
                        </div>
                        <div>
                            <div class="kap-contact-label">Correo</div>
                            <a href="mailto:kapitalyabolivia@gmail.com"
                               class="kap-contact-value" style="text-decoration:none;">
                                kapitalyabolivia@gmail.com
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Horario --}}
                <div class="kap-hours-card">
                    <div class="kap-hours-head">
                        <i class="bx bx-time-five"></i>Horario de atención
                    </div>
                    <div class="kap-hours-list">
                        <div class="kap-hours-row">
                            <span class="kap-hours-day">Lunes — Sábado</span>
                            <span class="kap-hours-time kap-hours-time--green">08:00 — 19:00</span>
                        </div>
                        <div class="kap-hours-row">
                            <span class="kap-hours-day">Domingo</span>
                            <span class="kap-hours-time kap-hours-time--gold">08:00 — 13:00</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Map column --}}
            <div class="col-lg-7 kap-fade-up" style="transition-delay:.1s;">
                <div class="kap-map-wrap" style="border-radius:var(--radius-lg);">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d239.12006877689288!2d-68.1210681488008!3d-16.479532316606306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sbo!4v1668611773954!5m2!1ses!2sbo"
                        width="100%" height="480" style="border:0;"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                        title="Ubicación Kapitalya Servicios Integrales">
                    </iframe>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;flex-wrap:wrap;gap:8px;">
                    <p style="font-size:12px;color:var(--kap-text-muted);margin:0;">
                        Av. Las Delicias Nro. 207-C · Zona Villa Fátima · La Paz, Bolivia
                    </p>
                    <a href="https://maps.google.com/?q=Kapitalya+Servicios+Integrales+La+Paz+Bolivia"
                       target="_blank" rel="noopener noreferrer"
                       class="kap-map-directions-btn" style="margin-top:0;font-size:12px;padding:8px 16px;">
                        <i class="bx bx-navigation"></i> Cómo llegar
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
