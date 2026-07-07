@extends('layout.master')
@section('title', 'Términos y Condiciones — Kapitalya Servicios Integrales')

@section('body')

{{-- HERO --}}
<div class="kap-about-hero" style="min-height:36vh;padding:70px 0 50px;">
    <div class="kap-about-hero-grid"></div>
    <div class="kap-about-hero-glow"></div>
    <div class="container" style="position:relative;z-index:2;text-align:center;">
        <span class="kap-about-label">Legal</span>
        <h1 class="kap-about-h2" style="font-size:clamp(28px,4vw,48px)!important;margin-top:12px;">
            Términos y Condiciones
        </h1>
        <p class="kap-about-hero-sub" style="margin-top:10px!important;font-size:14px!important;">
            Vigentes desde: {{ \Carbon\Carbon::parse('2025-06-02')->translatedFormat('d \d\e F \d\e Y') }}
        </p>
    </div>
</div>

{{-- CONTENT --}}
<section style="background:var(--kap-black);padding:80px 0 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Intro --}}
                <div class="kap-legal-intro">
                    <p>
                        Al acceder y utilizar los servicios de <strong style="color:var(--kap-text);">Kapitalya Servicios Integrales</strong>,
                        usted acepta los presentes Términos y Condiciones en su totalidad. Si no está de acuerdo con alguno de
                        estos términos, le solicitamos abstenerse de utilizar nuestros servicios.
                    </p>
                </div>

                @php
                $terms = [
                    ['1. Identificación de la empresa', 'bx-buildings', 'green',
                        '<p>Estos Términos y Condiciones regulan la relación entre:</p>
                         <ul>
                             <li><strong>Empresa:</strong> Kapitalya Servicios Integrales</li>
                             <li><strong>Propietario:</strong> Sergio Denis Troche Mayta</li>
                             <li><strong>Registro:</strong> Empresa Unipersonal registrada en SEPREC, Matrícula Nro. 670400030</li>
                             <li><strong>Dirección:</strong> Av. Las Delicias Nro. 207-C, Zona Villa Fátima, La Paz, Bolivia</li>
                             <li><strong>Contacto:</strong> kapitalyabolivia@gmail.com</li>
                         </ul>'],

                    ['2. Objeto y alcance de los servicios', 'bx-briefcase', 'green',
                        '<p>Kapitalya Servicios Integrales presta los siguientes servicios:</p>
                         <ul>
                             <li>Servicios comerciales y distribución de productos autorizados</li>
                             <li>Recargas electrónicas a operadoras bolivianas</li>
                             <li>Giros y transferencias nacionales de dinero</li>
                             <li>Apoyo operativo y consultoría administrativa</li>
                             <li>Gestión de talento humano</li>
                             <li>Servicios tecnológicos y digitales</li>
                             <li>Tipo de cambio de divisas (USD, EUR, CLP, PEN, BRL, ARS)</li>
                         </ul>
                         <p>Cada servicio puede estar sujeto a términos específicos adicionales que serán comunicados al momento de la contratación.</p>'],

                    ['3. Tasas de cambio y cotizaciones', 'bx-trending-up', 'gold',
                        '<p><strong class="kap-hl-gold">AVISO IMPORTANTE:</strong> Las tasas de cambio publicadas en este sitio web son <strong>referenciales y estimadas</strong>. No representan cotizaciones oficiales de operación ni comprometen a Kapitalya a operar a los precios indicados.</p>
                         <p>Las tasas reales de operación son determinadas al momento de cada transacción presencial en sucursal y pueden diferir de los valores publicados en línea.</p>
                         <p>Divisas operadas: USD · EUR · CLP · PEN · BRL · ARS</p>'],

                    ['4. Condiciones de uso del sitio web', 'bx-globe', 'green',
                        '<p>Al utilizar este sitio web, usted acepta:</p>
                         <ul>
                             <li>No utilizar la plataforma para actividades ilegales o contrarias a la normativa boliviana</li>
                             <li>No intentar acceder a áreas restringidas o sistemas internos de Kapitalya</li>
                             <li>No reproducir, distribuir o modificar el contenido sin autorización expresa</li>
                             <li>Proporcionar información veraz al realizar consultas o solicitudes</li>
                             <li>No sobrecargar los servidores con solicitudes automatizadas o ataques de denegación de servicio</li>
                         </ul>'],

                    ['5. Atención y horario de servicio', 'bx-time', 'green',
                        '<p>La atención presencial en sucursal se realiza en los siguientes horarios:</p>
                         <ul>
                             <li><strong>Lunes a Sábado:</strong> 08:00 — 19:00 hrs</li>
                             <li><strong>Domingos:</strong> 08:00 — 13:00 hrs</li>
                         </ul>
                         <p>Kapitalya se reserva el derecho de modificar estos horarios en fechas festivas o por razones operativas, con previo aviso cuando sea posible.</p>'],

                    ['6. Limitación de responsabilidad', 'bx-shield', 'gold',
                        '<p>Kapitalya no se responsabiliza por:</p>
                         <ul>
                             <li>Pérdidas derivadas del uso de las cotizaciones referenciales publicadas en este sitio como base para decisiones financieras</li>
                             <li>Interrupciones del servicio web por causas técnicas ajenas a nuestra voluntad</li>
                             <li>Contenido de sitios de terceros enlazados desde nuestra plataforma</li>
                             <li>Errores tipográficos o de actualización en la información publicada</li>
                         </ul>'],

                    ['7. Propiedad intelectual', 'bx-copyright', 'green',
                        '<p>Todo el contenido de este sitio web —incluyendo textos, diseños, logotipos, imágenes y código— es propiedad de Kapitalya Servicios Integrales y está protegido por las leyes de propiedad intelectual aplicables en Bolivia.</p>
                         <p>Queda prohibida su reproducción total o parcial sin autorización escrita previa de Kapitalya.</p>'],

                    ['8. Privacidad y datos personales', 'bx-lock-alt', 'green',
                        '<p>El tratamiento de sus datos personales se rige por nuestra <a href="' . route('privacy') . '" style="color:var(--kap-green);">Política de Privacidad</a>, que forma parte integral de estos Términos y Condiciones.</p>'],

                    ['9. Modificaciones', 'bx-edit', 'gold',
                        '<p>Kapitalya se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Los cambios entrarán en vigor desde su publicación en este sitio web.</p>
                         <p>El uso continuado de nuestros servicios tras la publicación de modificaciones implica la aceptación de los nuevos términos.</p>'],

                    ['10. Legislación aplicable', 'bx-scale', 'green',
                        '<p>Estos Términos y Condiciones se rigen por las leyes vigentes de la República Plurinacional de Bolivia. Cualquier controversia que surja en relación con estos términos será sometida a los tribunales competentes de la ciudad de La Paz, Bolivia.</p>'],

                    ['11. Contacto', 'bx-envelope', 'green',
                        '<p>Para consultas sobre estos Términos y Condiciones:</p>
                         <ul>
                             <li><strong>Correo:</strong> kapitalyabolivia@gmail.com</li>
                             <li><strong>WhatsApp:</strong> +591 64082967</li>
                             <li><strong>Telegram:</strong> +591 64082967</li>
                         </ul>'],
                ];
                @endphp

                @foreach($terms as $t)
                <div class="kap-legal-section kap-fade-up">
                    <div class="kap-legal-section-head">
                        <div class="kap-legal-icon kap-legal-icon--{{ $t[2] }}">
                            <i class="bx {{ $t[1] }}"></i>
                        </div>
                        <h3 class="kap-legal-section-title">{{ $t[0] }}</h3>
                    </div>
                    <div class="kap-legal-section-body">
                        {!! $t[3] !!}
                    </div>
                </div>
                @endforeach

                <p class="kap-legal-footer">
                    Kapitalya Servicios Integrales — Empresa Unipersonal registrada en Bolivia (SEPREC Nro. 670400030)<br>
                    La Paz, Bolivia · kapitalyabolivia@gmail.com
                </p>

            </div>
        </div>
    </div>
</section>

@endsection
