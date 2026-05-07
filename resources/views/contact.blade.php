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
                Contactá con <span style="color:var(--kap-green)">Kapitalya</span>
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
                <div style="background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:var(--radius);padding:24px;margin-bottom:16px;">
                    <div style="display:flex;align-items:flex-start;gap:16px;">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--kap-green-dim);border:1px solid rgba(0,200,150,.2);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bx bx-map-pin" style="color:var(--kap-green);font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:6px;">Dirección</div>
                            <div style="font-size:15px;font-weight:600;color:var(--kap-text);line-height:1.5;">
                                Av. Las Delicias Nro. 207-C<br>
                                Zona Villa Fátima, La Paz, Bolivia
                            </div>
                        </div>
                    </div>
                </div>

                {{-- WhatsApp --}}
                <a href="https://api.whatsapp.com/send?phone=59164082967&text=Hola%20Kapitalya%2C%20necesito%20informaci%C3%B3n"
                   target="_blank" rel="noopener noreferrer"
                   style="text-decoration:none;display:block;background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:var(--radius);
                          padding:24px;margin-bottom:16px;transition:var(--transition);"
                   onmouseenter="this.style.borderColor='rgba(37,211,102,.4)'"
                   onmouseleave="this.style.borderColor='var(--kap-border)'">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:42px;height:42px;border-radius:10px;background:rgba(37,211,102,.1);border:1px solid rgba(37,211,102,.2);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#25D366" width="20" height="20">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.557 4.118 1.531 5.845L.057 23.945l6.272-1.648A11.934 11.934 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.794 9.794 0 01-5.031-1.388l-.361-.214-3.722.977.995-3.634-.235-.374A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:4px;">WhatsApp</div>
                            <div style="font-size:16px;font-weight:700;color:#25D366;font-family:var(--font-mono);">+591 64082967</div>
                            <div style="font-size:12px;color:var(--kap-text-muted);margin-top:2px;">Toca para abrir WhatsApp</div>
                        </div>
                    </div>
                </a>

                {{-- Telegram --}}
                <a href="https://t.me/+59164082967"
                   target="_blank" rel="noopener noreferrer"
                   style="text-decoration:none;display:block;background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:var(--radius);
                          padding:24px;margin-bottom:16px;transition:var(--transition);"
                   onmouseenter="this.style.borderColor='rgba(0,136,204,.4)'"
                   onmouseleave="this.style.borderColor='var(--kap-border)'">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:42px;height:42px;border-radius:10px;background:rgba(0,136,204,.1);border:1px solid rgba(0,136,204,.2);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0088cc" width="20" height="20">
                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248l-2.012 9.483c-.148.664-.543.826-1.099.513l-3.048-2.245-1.47 1.415c-.163.163-.3.3-.614.3l.219-3.103 5.645-5.098c.245-.218-.054-.34-.38-.121L6.67 14.063 3.67 13.13c-.658-.206-.671-.658.138-.974l10.94-4.218c.547-.197 1.027.133.814.31z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:4px;">Telegram</div>
                            <div style="font-size:16px;font-weight:700;color:#0088cc;font-family:var(--font-mono);">+591 64082967</div>
                            <div style="font-size:12px;color:var(--kap-text-muted);margin-top:2px;">Toca para abrir Telegram</div>
                        </div>
                    </div>
                </a>

                {{-- Teléfono fijo --}}
                <div style="background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:var(--radius);padding:24px;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--kap-green-dim);border:1px solid rgba(0,200,150,.2);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bx bx-phone" style="color:var(--kap-green);font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:4px;">Teléfono</div>
                            <div style="font-size:15px;font-weight:600;color:var(--kap-text);font-family:var(--font-mono);">+591 2218312</div>
                        </div>
                    </div>
                </div>

                {{-- Email --}}
                <div style="background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:var(--radius);padding:24px;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:42px;height:42px;border-radius:10px;background:var(--kap-gold-dim);border:1px solid rgba(245,166,35,.2);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bx bx-envelope" style="color:var(--kap-gold);font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:4px;">Correo</div>
                            <a href="mailto:kapitalyabolivia@gmail.com" style="font-size:15px;font-weight:600;color:var(--kap-text);text-decoration:none;">
                                kapitalyabolivia@gmail.com
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Horario --}}
                <div style="background:var(--kap-surface-2);border:1px solid var(--kap-border);border-radius:var(--radius);padding:24px;">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:16px;">
                        <i class="bx bx-time-five" style="margin-right:6px;color:var(--kap-green);"></i>Horario de atención
                    </div>
                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;
                                    background:var(--kap-black);border:1px solid var(--kap-border);border-radius:8px;">
                            <span style="font-size:14px;color:var(--kap-text);">Lunes — Sábado</span>
                            <span style="font-family:var(--font-mono);font-size:13px;font-weight:700;color:var(--kap-green);">08:00 — 19:00</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 14px;
                                    background:var(--kap-black);border:1px solid var(--kap-border);border-radius:8px;">
                            <span style="font-size:14px;color:var(--kap-text);">Domingo</span>
                            <span style="font-family:var(--font-mono);font-size:13px;font-weight:700;color:var(--kap-gold);">08:00 — 13:00</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Map column --}}
            <div class="col-lg-7 kap-fade-up" style="transition-delay:.1s;">
                <div style="border-radius:var(--radius-lg);overflow:hidden;border:1px solid var(--kap-border);box-shadow:var(--shadow);">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d239.12006877689288!2d-68.1210681488008!3d-16.479532316606306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sbo!4v1668611773954!5m2!1ses!2sbo"
                        width="100%" height="480" style="border:0;display:block;filter:invert(90%) hue-rotate(180deg);"
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen
                        title="Ubicación Kapitalya Servicios Integrales">
                    </iframe>
                </div>
                <p style="font-size:12px;color:var(--kap-text-muted);text-align:center;margin-top:12px;">
                    Av. Las Delicias Nro. 207-C · Zona Villa Fátima · La Paz, Bolivia
                </p>
            </div>

        </div>
    </div>
</section>

@endsection
