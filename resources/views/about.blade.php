@extends('layout.master')
@section('title', 'Nosotros — Kapitalya Servicios Integrales')

@section('body')

{{-- ================================================================
     HERO
================================================================ --}}
<div class="kap-about-hero">
    <div class="kap-about-hero-grid"></div>
    <div class="kap-about-hero-glow"></div>
    <div class="container" style="position:relative;z-index:2;">
        <div class="text-center">
            <img src="{{ url('assets/images/kapitalya-wordmark-light.svg') }}"
                 alt="Kapitalya Servicios Integrales"
                 class="kap-about-hero-logo kap-logo-for-dark">
            <img src="{{ url('assets/images/kapitalya-wordmark-dark.svg') }}"
                 alt="Kapitalya Servicios Integrales"
                 class="kap-about-hero-logo kap-logo-for-light">

            <div class="kap-about-hero-pills">
                <span class="kap-about-pill">
                    <i class="bx bx-calendar"></i> 30+ años de trayectoria
                </span>
                <span class="kap-about-pill">
                    <i class="bx bx-map-pin"></i> La Paz, Bolivia
                </span>
                <span class="kap-about-pill kap-about-pill--green">
                    <i class="bx bx-file"></i> SEPREC · Matrícula 670400030
                </span>
            </div>

            <p class="kap-about-hero-sub">
                Más de 30 años evolucionando hacia el futuro.<br>
                Servicios comerciales, financieros, tecnológicos y administrativos para Bolivia.
            </p>
        </div>
    </div>
</div>

{{-- ================================================================
     DESCRIPCIÓN EMPRESARIAL
================================================================ --}}
<section style="background:linear-gradient(180deg,var(--kap-black) 0%,var(--kap-surface) 100%);padding:90px 0 70px;">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6 kap-fade-up">
                <span class="kap-about-label">Quiénes somos</span>
                <h2 class="kap-about-h2">
                    Más que un servicio.<br>
                    <span style="color:var(--kap-green)">Una solución integral.</span>
                </h2>
                <p style="font-size:16px;line-height:1.8;color:var(--kap-text-sec);margin-bottom:20px;">
                    Kapitalya Servicios Integrales es una empresa boliviana enfocada en la prestación
                    de servicios generales, comerciales, administrativos y tecnológicos orientados
                    a personas, emprendedores y microempresas.
                </p>
                <p style="font-size:16px;line-height:1.8;color:var(--kap-text-sec);margin-bottom:0;">
                    La empresa nace con el objetivo de integrar soluciones modernas, eficientes y accesibles,
                    combinando atención personalizada, herramientas digitales y optimización operativa.
                    Kapitalya busca posicionarse como una empresa versátil y escalable capaz de adaptarse
                    a las necesidades del mercado boliviano mediante innovación, tecnología y servicios integrales.
                </p>
            </div>

            <div class="col-lg-6 kap-fade-up" style="transition-delay:.12s;">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="kap-stat-card">
                            <div class="kap-stat-icon" style="color:var(--kap-green);">
                                <i class="bx bx-user"></i>
                            </div>
                            <div class="kap-stat-label">Propietario</div>
                            <div class="kap-stat-value" style="font-size:13px;font-family:var(--font-body);">
                                Sergio Denis<br>Troche Mayta
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="kap-stat-card">
                            <div class="kap-stat-icon" style="color:var(--kap-gold);">
                                <i class="bx bx-store"></i>
                            </div>
                            <div class="kap-stat-label">Áreas de servicio</div>
                            <div class="kap-stat-value" style="color:var(--kap-gold);">8+ servicios</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="kap-stat-card">
                            <div class="kap-stat-icon" style="color:var(--kap-green);">
                                <i class="bx bx-buildings"></i>
                            </div>
                            <div class="kap-stat-label">Razón Social</div>
                            <div class="kap-stat-value" style="font-size:13px;font-family:var(--font-body);">
                                Empresa Unipersonal
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="kap-stat-card">
                            <div class="kap-stat-icon" style="color:var(--kap-green);">
                                <i class="bx bx-map"></i>
                            </div>
                            <div class="kap-stat-label">Dirección</div>
                            <div class="kap-stat-value" style="font-size:12px;font-family:var(--font-body);line-height:1.5;">
                                Av. Las Delicias<br>Nro. 207-C, Villa Fátima
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     FOTOS DE LA EMPRESA
================================================================ --}}
<section style="background:var(--kap-surface);padding:0 0 70px;">
    <div class="container">
        <div class="row g-3">
            <div class="col-lg-6 kap-fade-up">
                <div style="border-radius:var(--radius-lg);overflow:hidden;border:1px solid var(--kap-border);height:260px;">
                    <img src="{{ url('assets/images/about/FotoExterior.jpeg') }}"
                         alt="Kapitalya — Vista exterior"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease;"
                         onmouseenter="this.style.transform='scale(1.04)'"
                         onmouseleave="this.style.transform='scale(1)'">
                </div>
                <p style="font-size:11px;color:var(--kap-text-muted);margin-top:8px;text-align:center;">Vista exterior · Zona Villa Fátima, La Paz</p>
            </div>
            <div class="col-lg-3 col-sm-6 kap-fade-up" style="transition-delay:.1s;">
                <div style="border-radius:var(--radius);overflow:hidden;border:1px solid var(--kap-border);height:260px;">
                    <img src="{{ url('assets/images/about/FotoInterior.jpeg') }}"
                         alt="Kapitalya — Interior"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease;"
                         onmouseenter="this.style.transform='scale(1.04)'"
                         onmouseleave="this.style.transform='scale(1)'">
                </div>
                <p style="font-size:11px;color:var(--kap-text-muted);margin-top:8px;text-align:center;">Interior de la sucursal</p>
            </div>
            <div class="col-lg-3 col-sm-6 kap-fade-up" style="transition-delay:.2s;">
                <div style="border-radius:var(--radius);overflow:hidden;border:1px solid var(--kap-border);height:260px;">
                    <img src="{{ url('assets/images/about/FotoTarjetasTelefonicas.jpeg') }}"
                         alt="Kapitalya — Recargas"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s ease;"
                         onmouseenter="this.style.transform='scale(1.04)'"
                         onmouseleave="this.style.transform='scale(1)'">
                </div>
                <p style="font-size:11px;color:var(--kap-text-muted);margin-top:8px;text-align:center;">Recargas telefónicas</p>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     HISTORIA / TIMELINE
================================================================ --}}
<section style="background:var(--kap-surface);padding:90px 0 80px;">
    <div class="container">
        <div class="row align-items-start g-5">

            {{-- Left: title + summary --}}
            <div class="col-lg-4 kap-fade-up" style="position:sticky;top:100px;">
                <span class="kap-about-label">Trayectoria</span>
                <h2 class="kap-about-h2">
                    Tres décadas de<br>
                    <span style="color:var(--kap-green)">evolución empresarial</span>
                </h2>
                <p style="color:var(--kap-text-sec);font-size:15px;line-height:1.8;">
                    Kapitalya representa la evolución de décadas de experiencia comercial,
                    combinando trayectoria, modernización y visión tecnológica para proyectarse
                    como una empresa integral orientada al futuro.
                </p>
                <div style="margin-top:28px;display:flex;flex-direction:column;gap:10px;">
                    <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:var(--kap-text-sec);">
                        <span style="width:10px;height:10px;border-radius:50%;background:var(--kap-text-muted);flex-shrink:0;"></span>
                        Hitos históricos
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:var(--kap-green);">
                        <span style="width:10px;height:10px;border-radius:50%;background:var(--kap-green);flex-shrink:0;
                                     box-shadow:0 0 8px rgba(0,200,150,0.6);"></span>
                        Etapa actual (2025)
                    </div>
                </div>
            </div>

            {{-- Right: timeline --}}
            <div class="col-lg-8 kap-fade-up" style="transition-delay:.12s;">
                <div class="kap-timeline">

                    {{-- 1990s --}}
                    <div class="kap-tl-item">
                        <div class="kap-tl-connector">
                            <div class="kap-tl-dot"></div>
                            <div class="kap-tl-line"></div>
                        </div>
                        <div class="kap-tl-content">
                            <div class="kap-tl-year">Década de 1990</div>
                            <div class="kap-tl-card">
                                <div class="kap-tl-tag" style="color:var(--kap-gold);background:var(--kap-gold-dim);border-color:rgba(245,166,35,.2);">
                                    <i class="bx bx-flag"></i> Fundación
                                </div>
                                <h5 class="kap-tl-title">Tromay Casa de Cambios</h5>
                                <p class="kap-tl-desc">
                                    Inicio de operaciones de manera informal bajo el nombre <strong style="color:var(--kap-text);">"Tromay Casa de Cambios"</strong>,
                                    enfocada principalmente en actividades cambiarias y servicios financieros operativos en La Paz, Bolivia.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 2010 --}}
                    <div class="kap-tl-item">
                        <div class="kap-tl-connector">
                            <div class="kap-tl-dot"></div>
                            <div class="kap-tl-line"></div>
                        </div>
                        <div class="kap-tl-content">
                            <div class="kap-tl-year">2010</div>
                            <div class="kap-tl-card">
                                <div class="kap-tl-tag" style="color:var(--kap-text-sec);background:var(--kap-surface-3);border-color:var(--kap-border-s);">
                                    <i class="bx bx-file"></i> Formalización
                                </div>
                                <h5 class="kap-tl-title">Registro y autorización oficial</h5>
                                <p class="kap-tl-desc">
                                    Obtención de formalización mediante registros y autorizaciones ante entidades regulatorias
                                    y comerciales en Bolivia, permitiendo desarrollar actividades dentro del marco formal empresarial.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Años intermedios --}}
                    <div class="kap-tl-item">
                        <div class="kap-tl-connector">
                            <div class="kap-tl-dot"></div>
                            <div class="kap-tl-line"></div>
                        </div>
                        <div class="kap-tl-content">
                            <div class="kap-tl-year">2010 — 2022</div>
                            <div class="kap-tl-card">
                                <div class="kap-tl-tag" style="color:var(--kap-text-sec);background:var(--kap-surface-3);border-color:var(--kap-border-s);">
                                    <i class="bx bx-refresh"></i> Evolución
                                </div>
                                <h5 class="kap-tl-title">Transformación y adaptación</h5>
                                <p class="kap-tl-desc">
                                    La empresa atravesó distintos procesos de transformación, adaptación y reestructuración
                                    ante cambios económicos, regulatorios y tecnológicos del mercado boliviano. Esta evolución
                                    permitió acumular experiencia operativa, conocimiento comercial y capacidad de adaptación empresarial.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 2023 --}}
                    <div class="kap-tl-item">
                        <div class="kap-tl-connector">
                            <div class="kap-tl-dot kap-tl-dot--mid"></div>
                            <div class="kap-tl-line"></div>
                        </div>
                        <div class="kap-tl-content">
                            <div class="kap-tl-year" style="color:var(--kap-green);">2023</div>
                            <div class="kap-tl-card" style="border-color:rgba(0,200,150,.15);background:rgba(0,200,150,.03);">
                                <div class="kap-tl-tag" style="color:var(--kap-green);background:var(--kap-green-dim);border-color:rgba(0,200,150,.2);">
                                    <i class="bx bx-rocket"></i> Refundación
                                </div>
                                <h5 class="kap-tl-title">Kapitalya Servicios Integrales</h5>
                                <p class="kap-tl-desc">
                                    Inicio del proceso de <strong style="color:var(--kap-green);">refundación y modernización estratégica</strong>,
                                    adoptando una nueva visión corporativa bajo el nombre de <strong style="color:var(--kap-text);">"Kapitalya Servicios Integrales"</strong>.
                                    El enfoque se amplió hacia servicios comerciales, tecnológicos, administrativos y soluciones
                                    integrales para personas, emprendedores y microempresas.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 2 junio 2025 — ACTUAL --}}
                    <div class="kap-tl-item kap-tl-item--active">
                        <div class="kap-tl-connector">
                            <div class="kap-tl-dot kap-tl-dot--active"></div>
                        </div>
                        <div class="kap-tl-content">
                            <div class="kap-tl-year" style="color:var(--kap-green);">2 jun 2025</div>
                            <div class="kap-tl-card kap-tl-card--active">
                                <div class="kap-tl-tag" style="color:var(--kap-green);background:var(--kap-green-dim);border-color:rgba(0,200,150,.25);">
                                    <i class="bx bx-check-shield"></i> Registro oficial
                                </div>
                                <h5 class="kap-tl-title">Registro en SEPREC</h5>
                                <p class="kap-tl-desc">
                                    <strong style="color:var(--kap-text);">KAPITALYA SERVICIOS INTEGRALES</strong> fue registrada oficialmente ante el
                                    <strong style="color:var(--kap-green);">Servicio Plurinacional de Registro de Comercio (SEPREC)</strong>
                                    como Empresa Unipersonal, consolidando formalmente una nueva etapa empresarial enfocada
                                    en innovación, crecimiento sostenible y transformación digital.
                                </p>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:14px;">
                                    <span style="font-size:11px;padding:4px 10px;background:var(--kap-black);border:1px solid rgba(0,200,150,.2);
                                                 border-radius:100px;color:var(--kap-green);font-weight:600;">
                                        Matrícula 670400030
                                    </span>
                                    <span style="font-size:11px;padding:4px 10px;background:var(--kap-black);border:1px solid var(--kap-border);
                                                 border-radius:100px;color:var(--kap-text-muted);">
                                        Empresa Unipersonal
                                    </span>
                                    <span style="font-size:11px;padding:4px 10px;background:var(--kap-black);border:1px solid var(--kap-border);
                                                 border-radius:100px;color:var(--kap-text-muted);">
                                        La Paz, Bolivia
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     MISIÓN & VISIÓN
================================================================ --}}
<section style="background:var(--kap-surface);padding:80px 0;">
    <div class="container">
        <div class="text-center mb-5 kap-fade-up">
            <span class="kap-about-label">Identidad corporativa</span>
            <h2 class="kap-about-h2">Misión &amp; Visión</h2>
        </div>
        <div class="row g-4">

            <div class="col-lg-6 kap-fade-up">
                <div class="kap-mv-card kap-mv-card--mission">
                    <div class="kap-mv-icon">
                        <img src="{{ url('assets/images/kapitalya-icon.svg') }}" alt="" style="width:36px;height:36px;">
                    </div>
                    <div class="kap-mv-tag" style="color:var(--kap-green);background:var(--kap-green-dim);border-color:rgba(0,200,150,.2);">
                        Misión
                    </div>
                    <p class="kap-mv-text">
                        Brindar soluciones comerciales, administrativas y tecnológicas de manera eficiente,
                        accesible y confiable, ayudando a mejorar la gestión y crecimiento de nuestros clientes
                        mediante atención personalizada e innovación constante.
                    </p>
                    <ul class="kap-mv-list">
                        <li><i class="bx bx-check-circle"></i> Eficiencia y accesibilidad</li>
                        <li><i class="bx bx-check-circle"></i> Atención personalizada</li>
                        <li><i class="bx bx-check-circle"></i> Innovación constante</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6 kap-fade-up" style="transition-delay:.1s;">
                <div class="kap-mv-card kap-mv-card--vision">
                    <div class="kap-mv-icon">
                        <img src="{{ url('assets/images/kapitalya-icon.svg') }}" alt="" style="width:36px;height:36px;">
                    </div>
                    <div class="kap-mv-tag" style="color:var(--kap-gold);background:var(--kap-gold-dim);border-color:rgba(245,166,35,.2);">
                        Visión
                    </div>
                    <p class="kap-mv-text">
                        Convertirse en una empresa boliviana referente en servicios integrales, innovación
                        y soluciones tecnológicas para personas, emprendedores y microempresas, destacándose
                        por su eficiencia, confianza y transformación digital.
                    </p>
                    <ul class="kap-mv-list">
                        <li><i class="bx bx-star" style="color:var(--kap-gold);"></i> Empresa referente en Bolivia</li>
                        <li><i class="bx bx-star" style="color:var(--kap-gold);"></i> Transformación digital</li>
                        <li><i class="bx bx-star" style="color:var(--kap-gold);"></i> Escalabilidad y crecimiento</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     VALORES CORPORATIVOS
================================================================ --}}
<section style="background:linear-gradient(135deg,var(--kap-black) 0%,rgba(0,200,150,0.04) 100%);padding:90px 0 70px;">
    <div class="container">
        <div class="text-center mb-5 kap-fade-up">
            <span class="kap-about-label">Cultura organizacional</span>
            <h2 class="kap-about-h2">Nuestros Valores</h2>
        </div>
        <div class="row g-4">

            <div class="col-lg-4 col-sm-6 kap-fade-up">
                <div class="kap-value-card">
                    <i class="bx bx-bulb" style="color:var(--kap-green);"></i>
                    <h3>Innovación</h3>
                    <p>Impulsamos soluciones modernas adaptadas a las necesidades actuales del mercado.</p>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.08s;">
                <div class="kap-value-card">
                    <i class="bx bx-shield-check" style="color:var(--kap-green);"></i>
                    <h3>Transparencia</h3>
                    <p>Actuamos con honestidad y claridad en todas nuestras operaciones y decisiones.</p>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="kap-value-card">
                    <i class="bx bx-award" style="color:var(--kap-gold);"></i>
                    <h3>Responsabilidad</h3>
                    <p>Cumplimos nuestros compromisos con profesionalismo y ética en cada entrega.</p>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.08s;">
                <div class="kap-value-card">
                    <i class="bx bx-trending-up" style="color:var(--kap-green);"></i>
                    <h3>Eficiencia</h3>
                    <p>Optimizamos recursos, tiempo y procesos para generar los mejores resultados.</p>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="kap-value-card">
                    <i class="bx bx-handshake" style="color:var(--kap-green);"></i>
                    <h3>Confianza</h3>
                    <p>Construimos relaciones sólidas y duraderas con nuestros clientes y aliados.</p>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 kap-fade-up" style="transition-delay:.24s;">
                <div class="kap-value-card">
                    <i class="bx bx-refresh" style="color:var(--kap-gold);"></i>
                    <h3>Adaptabilidad</h3>
                    <p>Nos ajustamos constantemente a los cambios tecnológicos y del mercado boliviano.</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     SERVICIOS PRINCIPALES
================================================================ --}}
<section style="background:var(--kap-surface);padding:90px 0 70px;">
    <div class="container">
        <div class="text-center mb-5 kap-fade-up">
            <span class="kap-about-label">Objeto empresarial</span>
            <h2 class="kap-about-h2">Servicios Principales</h2>
            <p style="color:var(--kap-text-sec);max-width:520px;margin:0 auto;font-size:15px;line-height:1.7;">
                Soluciones integrales en cinco áreas estratégicas para personas, emprendedores y microempresas bolivianas.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-lg-4 col-md-6 kap-fade-up">
                <div class="kap-service-card">
                    <div class="kap-service-letter" style="background:rgba(0,200,150,.1);color:var(--kap-green);border-color:rgba(0,200,150,.2);">A</div>
                    <h4>Servicios Comerciales</h4>
                    <ul class="kap-service-list">
                        <li>Venta de productos físicos</li>
                        <li>Comercialización de productos autorizados</li>
                        <li>Distribución de servicios de terceros</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 kap-fade-up" style="transition-delay:.08s;">
                <div class="kap-service-card">
                    <div class="kap-service-letter" style="background:rgba(0,200,150,.1);color:var(--kap-green);border-color:rgba(0,200,150,.2);">B</div>
                    <h4>Servicios Digitales</h4>
                    <ul class="kap-service-list">
                        <li>Recargas electrónicas</li>
                        <li>Servicios digitales complementarios</li>
                        <li>Atención y soporte tecnológico</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="kap-service-card">
                    <div class="kap-service-letter" style="background:rgba(245,166,35,.1);color:var(--kap-gold);border-color:rgba(245,166,35,.2);">C</div>
                    <h4>Servicios Financieros Operativos</h4>
                    <ul class="kap-service-list">
                        <li>Giros nacionales</li>
                        <li>Servicios operativos de apoyo comercial</li>
                        <li>Gestión administrativa básica</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 offset-lg-2 kap-fade-up" style="transition-delay:.08s;">
                <div class="kap-service-card">
                    <div class="kap-service-letter" style="background:rgba(0,200,150,.1);color:var(--kap-green);border-color:rgba(0,200,150,.2);">D</div>
                    <h4>Asesoría Empresarial</h4>
                    <ul class="kap-service-list">
                        <li>Gestión de talento humano</li>
                        <li>Organización administrativa</li>
                        <li>Soporte operativo para microempresas</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 kap-fade-up" style="transition-delay:.16s;">
                <div class="kap-service-card">
                    <div class="kap-service-letter" style="background:rgba(245,166,35,.1);color:var(--kap-gold);border-color:rgba(245,166,35,.2);">E</div>
                    <h4>Servicios Tecnológicos</h4>
                    <ul class="kap-service-list">
                        <li>Soporte tecnológico</li>
                        <li>Digitalización básica</li>
                        <li>Automatización de procesos</li>
                        <li>Desarrollo de soluciones digitales</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     PÚBLICO OBJETIVO + DIFERENCIADORES
================================================================ --}}
<section style="background:linear-gradient(180deg,var(--kap-surface) 0%,var(--kap-black) 100%);padding:90px 0 70px;">
    <div class="container">
        <div class="row g-5">

            <div class="col-lg-5 kap-fade-up">
                <span class="kap-about-label">A quién servimos</span>
                <h2 class="kap-about-h2" style="font-size:clamp(26px,3vw,38px)!important;">
                    Público Objetivo
                </h2>
                <p style="color:var(--kap-text-sec);font-size:15px;line-height:1.7;margin-bottom:28px;">
                    Kapitalya está orientada a quienes necesitan soluciones accesibles, modernas y confiables.
                </p>
                <div class="kap-audience-grid">
                    <span class="kap-audience-tag"><i class="bx bx-user"></i> Personas naturales</span>
                    <span class="kap-audience-tag"><i class="bx bx-rocket"></i> Emprendedores</span>
                    <span class="kap-audience-tag"><i class="bx bx-store"></i> Comerciantes</span>
                    <span class="kap-audience-tag"><i class="bx bx-buildings"></i> Microempresas</span>
                    <span class="kap-audience-tag"><i class="bx bx-home-heart"></i> Negocios familiares</span>
                    <span class="kap-audience-tag"><i class="bx bx-laptop"></i> Trabajadores independientes</span>
                    <span class="kap-audience-tag"><i class="bx bx-basket"></i> Pequeños comercios</span>
                </div>
            </div>

            <div class="col-lg-7 kap-fade-up" style="transition-delay:.12s;">
                <span class="kap-about-label">Por qué elegirnos</span>
                <h2 class="kap-about-h2" style="font-size:clamp(26px,3vw,38px)!important;">
                    Diferenciadores
                </h2>
                <div class="kap-differentiators">

                    <div class="kap-diff-item">
                        <div class="kap-diff-num">01</div>
                        <div>
                            <h5>Atención Personalizada</h5>
                            <p>Enfoque cercano y adaptado a cada cliente, sin procesos burocráticos.</p>
                        </div>
                    </div>

                    <div class="kap-diff-item">
                        <div class="kap-diff-num">02</div>
                        <div>
                            <h5>Integración de Servicios</h5>
                            <p>Múltiples soluciones comerciales, digitales y administrativas en un solo lugar.</p>
                        </div>
                    </div>

                    <div class="kap-diff-item">
                        <div class="kap-diff-num">03</div>
                        <div>
                            <h5>Enfoque Tecnológico</h5>
                            <p>Uso de herramientas digitales y automatización para optimizar resultados.</p>
                        </div>
                    </div>

                    <div class="kap-diff-item">
                        <div class="kap-diff-num">04</div>
                        <div>
                            <h5>Adaptación al Mercado Boliviano</h5>
                            <p>Servicios diseñados para las necesidades reales del entorno local.</p>
                        </div>
                    </div>

                    <div class="kap-diff-item">
                        <div class="kap-diff-num">05</div>
                        <div>
                            <h5>Escalabilidad</h5>
                            <p>Capacidad de expansión hacia nuevos servicios y modelos digitales.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     ESTRUCTURA OPERATIVA
================================================================ --}}
<section style="background:var(--kap-surface);padding:90px 0 70px;">
    <div class="container">
        <div class="text-center mb-5 kap-fade-up">
            <span class="kap-about-label">Organización</span>
            <h2 class="kap-about-h2">Estructura Operativa</h2>
        </div>
        <div class="row g-4 justify-content-center">

            <div class="col-lg-4 col-md-6 kap-fade-up">
                <div class="kap-area-card">
                    <div class="kap-area-icon">
                        <i class="bx bx-crown" style="color:var(--kap-gold);"></i>
                    </div>
                    <h4>Dirección General</h4>
                    <ul>
                        <li>Estrategia empresarial</li>
                        <li>Gestión administrativa</li>
                        <li>Innovación y crecimiento</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 kap-fade-up" style="transition-delay:.1s;">
                <div class="kap-area-card">
                    <div class="kap-area-icon">
                        <i class="bx bx-group" style="color:var(--kap-green);"></i>
                    </div>
                    <h4>Área Operativa</h4>
                    <ul>
                        <li>Atención al cliente</li>
                        <li>Operaciones comerciales</li>
                        <li>Gestión de servicios</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 kap-fade-up" style="transition-delay:.2s;">
                <div class="kap-area-card">
                    <div class="kap-area-icon">
                        <i class="bx bx-chip" style="color:var(--kap-green);"></i>
                    </div>
                    <h4>Área Tecnológica</h4>
                    <ul>
                        <li>Soporte digital y sistemas</li>
                        <li>Automatización</li>
                        <li>Desarrollo tecnológico</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     INFRAESTRUCTURA + ENFOQUE TECNOLÓGICO
================================================================ --}}
<section style="background:linear-gradient(135deg,var(--kap-black) 0%,rgba(59,130,246,0.04) 100%);padding:90px 0 70px;">
    <div class="container">
        <div class="row g-5 align-items-start">

            <div class="col-lg-6 kap-fade-up">
                <span class="kap-about-label">Infraestructura</span>
                <h2 class="kap-about-h2" style="font-size:clamp(26px,3vw,38px)!important;">
                    Nuestras capacidades
                </h2>
                <div class="kap-infra-grid">
                    <div class="kap-infra-item">
                        <i class="bx bx-building-house" style="color:var(--kap-green);"></i>
                        <span>Oficina comercial</span>
                    </div>
                    <div class="kap-infra-item">
                        <i class="bx bx-desktop" style="color:var(--kap-green);"></i>
                        <span>Equipos informáticos</span>
                    </div>
                    <div class="kap-infra-item">
                        <i class="bx bx-camera" style="color:var(--kap-gold);"></i>
                        <span>Sistema de seguridad</span>
                    </div>
                    <div class="kap-infra-item">
                        <i class="bx bx-lock-alt" style="color:var(--kap-gold);"></i>
                        <span>Caja fuerte</span>
                    </div>
                    <div class="kap-infra-item">
                        <i class="bx bx-wifi" style="color:var(--kap-green);"></i>
                        <span>Conectividad digital</span>
                    </div>
                    <div class="kap-infra-item">
                        <i class="bx bx-user-check" style="color:var(--kap-green);"></i>
                        <span>Espacio de atención</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 kap-fade-up" style="transition-delay:.12s;">
                <span class="kap-about-label">Tecnología</span>
                <h2 class="kap-about-h2" style="font-size:clamp(26px,3vw,38px)!important;">
                    Enfoque Tecnológico
                </h2>
                <p style="color:var(--kap-text-sec);font-size:15px;line-height:1.7;margin-bottom:24px;">
                    Kapitalya incorpora herramientas modernas para optimizar cada proceso y escalar sus servicios.
                </p>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach(['Optimizar procesos operativos', 'Mejorar la atención al cliente', 'Automatizar operaciones repetitivas', 'Desarrollar soluciones digitales', 'Escalar servicios empresariales'] as $tech)
                    <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;
                                background:var(--kap-surface-2);border:1px solid var(--kap-border);
                                border-radius:8px;">
                        <div style="width:28px;height:28px;border-radius:6px;background:var(--kap-green-dim);
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bx bx-check" style="color:var(--kap-green);font-size:16px;"></i>
                        </div>
                        <span style="font-size:14px;color:var(--kap-text);">{{ $tech }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     PROYECCIÓN FUTURA
================================================================ --}}
<section style="background:var(--kap-surface);padding:90px 0 70px;">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-5 kap-fade-up">
                <span class="kap-about-label">Roadmap</span>
                <h2 class="kap-about-h2">
                    Proyección <span style="color:var(--kap-green)">Futura</span>
                </h2>
                <p style="color:var(--kap-text-sec);font-size:16px;line-height:1.8;">
                    Kapitalya proyecta una expansión progresiva hacia servicios de mayor valor, integrando
                    inteligencia tecnológica y modelos regulados para el mercado boliviano.
                </p>
                <div style="margin-top:28px;padding:20px;background:var(--kap-surface-2);
                            border:1px solid var(--kap-border);border-radius:12px;">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;
                                color:var(--kap-text-muted);margin-bottom:8px;">Cultura organizacional</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach(['Innovación continua','Aprendizaje constante','Adaptabilidad','Orientación al cliente','Crecimiento sostenible','Mejora permanente'] as $c)
                        <span style="font-size:12px;padding:4px 10px;background:var(--kap-black);
                                     border:1px solid var(--kap-border);border-radius:100px;color:var(--kap-text-sec);">
                            {{ $c }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-7 kap-fade-up" style="transition-delay:.12s;">
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach([
                        ['Servicios financieros especializados', 'bx-credit-card', 'green'],
                        ['Soluciones digitales empresariales', 'bx-code-alt', 'green'],
                        ['Inteligencia artificial aplicada', 'bx-brain', 'gold'],
                        ['Automatización comercial', 'bx-bot', 'green'],
                        ['Plataformas tecnológicas', 'bx-layer', 'green'],
                        ['Servicios fintech y operativos regulados', 'bx-shield', 'gold'],
                    ] as $i => [$label, $icon, $color])
                    <div class="kap-roadmap-item">
                        <div class="kap-roadmap-num" style="color:var(--kap-{{ $color }});border-color:rgba({{ $color === 'green' ? '0,200,150' : '245,166,35' }},.2);background:var(--kap-{{ $color }}-dim);">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div style="width:36px;height:36px;border-radius:8px;background:var(--kap-surface-2);
                                    border:1px solid var(--kap-border);display:flex;align-items:center;
                                    justify-content:center;flex-shrink:0;">
                            <i class="bx {{ $icon }}" style="color:var(--kap-{{ $color }});font-size:18px;"></i>
                        </div>
                        <span style="font-size:15px;color:var(--kap-text);">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     IDENTIDAD DE MARCA
================================================================ --}}
<section style="background:linear-gradient(180deg,var(--kap-surface) 0%,var(--kap-black) 100%);padding:70px 0;">
    <div class="container">
        <div class="text-center mb-5 kap-fade-up">
            <span class="kap-about-label">Branding</span>
            <h2 class="kap-about-h2">Identidad de Marca</h2>
            <p style="color:var(--kap-text-sec);font-size:15px;max-width:500px;margin:0 auto;line-height:1.7;">
                Una marca que transmite modernidad, crecimiento, tecnología y confianza en cada punto de contacto.
            </p>
        </div>

        <div class="row g-4 align-items-center kap-fade-up">

            <div class="col-lg-6">
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:32px;">
                    @foreach(['Modernidad','Crecimiento','Tecnología','Soluciones','Confianza','Evolución empresarial','Profesional','Minimalista','Eficiente','Confiable'] as $word)
                    <span class="kap-brand-word">{{ $word }}</span>
                    @endforeach
                </div>
                <p style="color:var(--kap-text-sec);font-size:15px;line-height:1.7;">
                    Kapitalya proyecta una imagen <strong style="color:var(--kap-text);">profesional, tecnológica y moderna</strong>
                    combinando un diseño minimalista con eficiencia visual y confianza percibida en cada interacción.
                </p>
            </div>

            <div class="col-lg-6">
                <div style="background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:16px;padding:32px;">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--kap-text-muted);margin-bottom:20px;">
                        Sistema de logos
                    </div>
                    <div style="display:flex;flex-direction:column;gap:20px;">

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px;
                                    background:var(--kap-surface-2);border-radius:10px;border:1px solid var(--kap-border);">
                            <img src="{{ url('assets/images/kapitalya-wordmark-light.svg') }}" alt="Logo — tema oscuro" class="kap-logo-for-dark" style="height:40px;">
                            <img src="{{ url('assets/images/kapitalya-wordmark-dark.svg') }}" alt="Logo — tema claro" class="kap-logo-for-light" style="height:40px;">
                            <span style="font-size:11px;color:var(--kap-text-muted);">Wordmark</span>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px;
                                    background:var(--kap-surface-2);border-radius:10px;border:1px solid var(--kap-border);">
                            <img src="{{ url('assets/images/kapitalya-icon.svg') }}" alt="Ícono" style="height:40px;">
                            <span style="font-size:11px;color:var(--kap-text-muted);">Ícono / Mark</span>
                        </div>

                        <div style="display:flex;gap:12px;padding:16px;background:var(--kap-surface-3);
                                    border-radius:10px;border:1px solid var(--kap-border);align-items:center;justify-content:space-between;">
                            <img src="{{ url('assets/images/kapitalya-icon-mono-white.svg') }}" class="kap-logo-for-dark" alt="Ícono mono blanco" style="height:40px;">
                            <img src="{{ url('assets/images/kapitalya-icon-mono-dark.svg') }}" class="kap-logo-for-light" alt="Ícono mono oscuro" style="height:40px;">
                            <span style="font-size:11px;color:var(--kap-text-muted);">Mono / Mark</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ================================================================
     DATOS CORPORATIVOS LEGALES
================================================================ --}}
<section style="background:var(--kap-surface);padding:70px 0 90px;">
    <div class="container">
        <div class="text-center mb-5 kap-fade-up">
            <span class="kap-about-label">Información Legal</span>
            <h2 class="kap-about-h2">Datos Corporativos</h2>
        </div>
        <div class="row justify-content-center kap-fade-up">
            <div class="col-lg-8">
                <div style="background:var(--kap-surface-2);border:1px solid var(--kap-border);border-radius:var(--radius-lg);overflow:hidden;">

                    @foreach([
                        ['Nombre Comercial',    'Kapitalya Servicios Integrales', 'bx-store',     'text'],
                        ['Razón Social',        'Empresa Unipersonal',            'bx-buildings', 'text'],
                        ['Propietario',         'Sergio Denis Troche Mayta',      'bx-user',      'text'],
                        ['Matrícula SEPREC',    '670400030',                      'bx-file',      'green'],
                        ['Ubicación',           'La Paz, Bolivia',                'bx-map-pin',   'text'],
                        ['Dirección Comercial', 'Zona Villa Fátima, Av. Las Delicias Nro. 207-C', 'bx-map', 'text'],
                        ['WhatsApp / Telegram', '+591 64082967 · +591 64082967',  'bx-phone',     'green'],
                    ] as $row)
                    <div class="kap-legal-row">
                        <div class="kap-legal-key">
                            <i class="bx {{ $row[2] }}" style="color:{{ $row[3] === 'green' ? 'var(--kap-green)' : ($row[3] === 'gold' ? 'var(--kap-gold)' : 'var(--kap-text-muted)') }};"></i>
                            {{ $row[0] }}
                        </div>
                        <div class="kap-legal-val" style="{{ $row[3] === 'green' ? 'color:var(--kap-green);' : ($row[3] === 'gold' ? 'color:var(--kap-gold);' : '') }}">
                            {{ $row[1] }}
                        </div>
                    </div>
                    @endforeach

                </div>

                <p style="font-size:12px;color:var(--kap-text-muted);text-align:center;margin-top:16px;">
                    Información corporativa oficial. Empresa registrada en Bolivia.
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
