{{--
    partials/trust.blade.php — Confianza / prueba social con HECHOS REALES únicamente.

    NO contiene testimonios inventados ni recuentos de reseñas ficticios. Solo hechos
    verificables de Tromay Casa de Cambio. El botón lleva a las reseñas REALES de Google.

    Estilo Kapitalya (kap-*, oscuro). Accesible (section con aria-labelledby, lista de
    hechos, iconos decorativos con aria-hidden, botón con rel noopener).
--}}
<section id="confianza" aria-labelledby="kap-trust-title"
         style="background:var(--kap-black);padding:100px 0 80px;position:relative;overflow:hidden;">
    <div class="container">
        <div class="section-title text-center" style="margin-bottom:40px;">
            <span class="section-title"><span>Por qué confiar en Tromay</span></span>
            <h2 id="kap-trust-title" style="color:var(--kap-text,#e6e8f0);margin-top:10px;">
                Décadas cambiando divisas <span class="kap-hl-gold">en persona</span>, en La Paz
            </h2>
            <p style="max-width:640px;margin:14px auto 0;color:var(--kap-text-muted,#8a90a6);">
                Somos una casa de cambio física, registrada y con atención presencial. Estos son
                los hechos — sin letra chica.
            </p>
        </div>

        <ul style="list-style:none;margin:0;padding:0;display:grid;gap:18px;
                   grid-template-columns:repeat(auto-fit,minmax(240px,1fr));">
            <li style="background:var(--kap-surface,#12172e);border:1px solid var(--kap-border,rgba(255,255,255,.10));
                       border-radius:14px;padding:22px;">
                <div aria-hidden="true" style="font-size:24px;color:var(--kap-hl-gold,#f59e0b);margin-bottom:10px;">
                    <i class="bx bx-time-five"></i>
                </div>
                <h3 style="font-size:17px;margin:0 0 6px;color:var(--kap-text,#e6e8f0);">Trayectoria de décadas</h3>
                <p style="margin:0;font-size:14px;color:var(--kap-text-muted,#8a90a6);">
                    Décadas cambiando divisas en La Paz, con atención presencial.
                </p>
            </li>

            <li style="background:var(--kap-surface,#12172e);border:1px solid var(--kap-border,rgba(255,255,255,.10));
                       border-radius:14px;padding:22px;">
                <div aria-hidden="true" style="font-size:24px;color:var(--kap-hl-green,#22c55e);margin-bottom:10px;">
                    <i class="bx bx-shield-quarter"></i>
                </div>
                <h3 style="font-size:17px;margin:0 0 6px;color:var(--kap-text,#e6e8f0);">Empresa registrada</h3>
                <p style="margin:0;font-size:14px;color:var(--kap-text-muted,#8a90a6);">
                    Registro SEPREC <strong style="color:var(--kap-text,#e6e8f0);">670400030</strong>.
                    Negocio formal y verificable.
                </p>
            </li>

            <li style="background:var(--kap-surface,#12172e);border:1px solid var(--kap-border,rgba(255,255,255,.10));
                       border-radius:14px;padding:22px;">
                <div aria-hidden="true" style="font-size:24px;color:var(--kap-hl-gold,#f59e0b);margin-bottom:10px;">
                    <i class="bx bxs-location-plus"></i>
                </div>
                <h3 style="font-size:17px;margin:0 0 6px;color:var(--kap-text,#e6e8f0);">Sucursal física</h3>
                <p style="margin:0;font-size:14px;color:var(--kap-text-muted,#8a90a6);">
                    Villa Fátima — Av. Las Delicias Nro. 207-C, La Paz. Atención en persona,
                    cara a cara.
                </p>
            </li>

            <li style="background:var(--kap-surface,#12172e);border:1px solid var(--kap-border,rgba(255,255,255,.10));
                       border-radius:14px;padding:22px;">
                <div aria-hidden="true" style="font-size:24px;color:var(--kap-hl-green,#22c55e);margin-bottom:10px;">
                    <i class="bx bx-transfer-alt"></i>
                </div>
                <h3 style="font-size:17px;margin:0 0 6px;color:var(--kap-text,#e6e8f0);">Tasas en vivo</h3>
                <p style="margin:0;font-size:14px;color:var(--kap-text-muted,#8a90a6);">
                    Las cotizaciones se actualizan en tiempo real desde
                    <strong style="color:var(--kap-text,#e6e8f0);">Forex ERP</strong>.
                    La tasa que ves es la que aplicás.
                </p>
            </li>
        </ul>

        <div class="text-center" style="margin-top:40px;">
            {{-- TODO: reemplazar el href por la URL real de la ficha de Google Business de Tromay.
                 Placeholder: búsqueda del negocio en Google Maps. --}}
            <a href="https://www.google.com/maps/search/Tromay+Casa+de+Cambio+La+Paz"
               target="_blank" rel="noopener noreferrer"
               class="kap-btn kap-btn--outline"
               aria-label="Ver las reseñas de Tromay en Google Maps (se abre en una pestaña nueva)"
               style="display:inline-flex;align-items:center;gap:9px;padding:13px 26px;border-radius:10px;
                      border:1px solid var(--kap-border,rgba(255,255,255,.18));color:var(--kap-text,#e6e8f0);
                      background:transparent;text-decoration:none;font-weight:600;font-size:14.5px;">
                <i class="bx bxl-google" aria-hidden="true" style="font-size:20px;color:var(--kap-hl-gold,#f59e0b);"></i>
                Ver reseñas en Google Maps
            </a>
        </div>
    </div>
</section>
