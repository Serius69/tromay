@extends('layout.master')
@section('title', 'Política de Privacidad — Kapitalya Servicios Integrales')

@section('body')

{{-- HERO --}}
<div class="kap-about-hero" style="min-height:36vh;padding:70px 0 50px;">
    <div class="kap-about-hero-grid"></div>
    <div class="kap-about-hero-glow"></div>
    <div class="container" style="position:relative;z-index:2;text-align:center;">
        <span class="kap-about-label">Legal</span>
        <h1 class="kap-about-h2" style="font-size:clamp(28px,4vw,48px)!important;margin-top:12px;">
            Política de Privacidad
        </h1>
        <p class="kap-about-hero-sub" style="margin-top:10px!important;font-size:14px!important;">
            Última actualización: {{ \Carbon\Carbon::parse('2025-06-02')->translatedFormat('d \d\e F \d\e Y') }}
        </p>
    </div>
</div>

{{-- CONTENT --}}
<section style="background:var(--kap-black);padding:80px 0 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @php
                $sections = [
                    ['1. Responsable del tratamiento', 'bx-buildings', 'green',
                        '<p>El responsable del tratamiento de sus datos personales es:</p>
                         <ul>
                             <li><strong>Empresa:</strong> Kapitalya Servicios Integrales</li>
                             <li><strong>Propietario:</strong> Sergio Denis Troche Mayta</li>
                             <li><strong>Registro:</strong> Matrícula de Comercio SEPREC Nro. 670400030</li>
                             <li><strong>Dirección:</strong> Av. Las Delicias Nro. 207-C, Zona Villa Fátima, La Paz, Bolivia</li>
                             <li><strong>Correo:</strong> kapitalyabolivia@gmail.com</li>
                         </ul>'],

                    ['2. Información que recopilamos', 'bx-data', 'green',
                        '<p>Kapitalya puede recopilar la siguiente información cuando usted interactúa con nuestra plataforma o servicios:</p>
                         <ul>
                             <li>Nombre completo y datos de identificación (CI/DNI)</li>
                             <li>Número de teléfono y correo electrónico</li>
                             <li>Información de contacto proporcionada voluntariamente</li>
                             <li>Datos de navegación (páginas visitadas, tiempo de sesión, dirección IP)</li>
                             <li>Información relacionada con operaciones o consultas realizadas</li>
                         </ul>
                         <p>No recopilamos información financiera sensible (números de tarjeta, cuentas bancarias) a través de este sitio web.</p>'],

                    ['3. Finalidad del tratamiento', 'bx-target-lock', 'gold',
                        '<p>Los datos recopilados son utilizados exclusivamente para:</p>
                         <ul>
                             <li>Brindar atención y responder consultas de clientes</li>
                             <li>Gestionar operaciones y servicios solicitados</li>
                             <li>Mejorar la experiencia del usuario en nuestra plataforma</li>
                             <li>Cumplir con obligaciones legales y regulatorias en Bolivia</li>
                             <li>Enviar información relevante sobre nuestros servicios (solo con consentimiento)</li>
                         </ul>'],

                    ['4. Base legal del tratamiento', 'bx-shield-check', 'green',
                        '<p>El tratamiento de sus datos se basa en:</p>
                         <ul>
                             <li><strong>Consentimiento:</strong> cuando usted proporciona sus datos voluntariamente.</li>
                             <li><strong>Ejecución contractual:</strong> cuando los datos son necesarios para prestar los servicios solicitados.</li>
                             <li><strong>Obligación legal:</strong> cuando la normativa boliviana vigente lo requiere.</li>
                             <li><strong>Interés legítimo:</strong> para la mejora de nuestros servicios y seguridad operativa.</li>
                         </ul>'],

                    ['5. Cookies y tecnologías similares', 'bx-cookie', 'gold',
                        '<p>Nuestro sitio web puede utilizar cookies técnicas y de sesión para garantizar el correcto funcionamiento de la plataforma. Estas cookies no almacenan información personal identificable y son necesarias para la navegación.</p>
                         <p>No utilizamos cookies de seguimiento ni publicidad de terceros. Puede configurar su navegador para rechazar cookies, aunque esto puede afectar el funcionamiento de algunas funcionalidades.</p>'],

                    ['6. Compartir información con terceros', 'bx-share-alt', 'green',
                        '<p>Kapitalya <strong>no vende, alquila ni transfiere</strong> sus datos personales a terceros con fines comerciales.</p>
                         <p>Sus datos pueden ser compartidos únicamente en los siguientes casos:</p>
                         <ul>
                             <li>Cuando sea requerido por autoridades competentes bajo mandato legal</li>
                             <li>Con proveedores de servicios tecnológicos que actúan bajo instrucciones de Kapitalya y están sujetos a acuerdos de confidencialidad</li>
                         </ul>'],

                    ['7. Seguridad de los datos', 'bx-lock', 'green',
                        '<p>Implementamos medidas técnicas y organizativas razonables para proteger sus datos personales contra acceso no autorizado, pérdida, alteración o divulgación, incluyendo:</p>
                         <ul>
                             <li>Conexiones cifradas (HTTPS/TLS)</li>
                             <li>Control de acceso restringido a sistemas internos</li>
                             <li>Almacenamiento seguro en servidores protegidos</li>
                         </ul>'],

                    ['8. Derechos del titular de datos', 'bx-user-check', 'gold',
                        '<p>De acuerdo con la normativa boliviana aplicable, usted tiene derecho a:</p>
                         <ul>
                             <li><strong>Acceder</strong> a sus datos personales que tratamos</li>
                             <li><strong>Rectificar</strong> información incorrecta o desactualizada</li>
                             <li><strong>Solicitar la eliminación</strong> de sus datos cuando ya no sean necesarios</li>
                             <li><strong>Oponerse</strong> al tratamiento en determinadas circunstancias</li>
                             <li><strong>Revocar el consentimiento</strong> otorgado en cualquier momento</li>
                         </ul>
                         <p>Para ejercer estos derechos, puede contactarnos en: <strong>kapitalyabolivia@gmail.com</strong></p>'],

                    ['9. Retención de datos', 'bx-time', 'green',
                        '<p>Conservamos sus datos personales durante el tiempo necesario para cumplir con las finalidades descritas en esta política, o durante el período exigido por la legislación boliviana vigente.</p>
                         <p>Una vez transcurrido dicho período, los datos serán eliminados o anonimizados de forma segura.</p>'],

                    ['10. Modificaciones a esta política', 'bx-edit', 'gold',
                        '<p>Kapitalya se reserva el derecho de actualizar esta Política de Privacidad en cualquier momento. Cualquier cambio será publicado en esta página con la fecha de actualización correspondiente.</p>
                         <p>Le recomendamos revisar periódicamente esta política para mantenerse informado sobre cómo protegemos su información.</p>'],

                    ['11. Contacto', 'bx-envelope', 'green',
                        '<p>Para consultas, solicitudes o reclamos relacionados con el tratamiento de sus datos personales, puede contactarnos a través de:</p>
                         <ul>
                             <li><strong>Correo:</strong> kapitalyabolivia@gmail.com</li>
                             <li><strong>WhatsApp:</strong> +591 64082967</li>
                             <li><strong>Telegram:</strong> +591 64082967</li>
                             <li><strong>Dirección:</strong> Av. Las Delicias 207-C, Zona Villa Fátima, La Paz, Bolivia</li>
                         </ul>'],
                ];
                @endphp

                @foreach($sections as $i => $s)
                <div style="background:var(--kap-surface);border:1px solid var(--kap-border);border-radius:var(--radius);
                            padding:28px;margin-bottom:16px;kap-fade-up;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                        <div style="width:36px;height:36px;border-radius:8px;flex-shrink:0;
                                    background:{{ $s[2] === 'green' ? 'var(--kap-green-dim)' : 'var(--kap-gold-dim)' }};
                                    border:1px solid {{ $s[2] === 'green' ? 'rgba(0,200,150,.2)' : 'rgba(245,166,35,.2)' }};
                                    display:flex;align-items:center;justify-content:center;">
                            <i class="bx {{ $s[1] }}" style="color:{{ $s[2] === 'green' ? 'var(--kap-green)' : 'var(--kap-gold)' }};font-size:18px;"></i>
                        </div>
                        <h3 style="font-family:var(--font-display)!important;font-size:17px!important;font-weight:700!important;
                                   color:var(--kap-text)!important;margin:0!important;">{{ $s[0] }}</h3>
                    </div>
                    <div style="font-size:14px;color:var(--kap-text-sec);line-height:1.75;">
                        {!! $s[3] !!}
                    </div>
                </div>
                @endforeach

                <p style="font-size:12px;color:var(--kap-text-muted);text-align:center;margin-top:24px;line-height:1.6;">
                    Kapitalya Servicios Integrales — Empresa Unipersonal registrada en Bolivia (SEPREC Nro. 670400030)<br>
                    La Paz, Bolivia · kapitalyabolivia@gmail.com
                </p>

            </div>
        </div>
    </div>
</section>

@endsection
