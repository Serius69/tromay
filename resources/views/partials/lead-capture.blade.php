{{-- ================================================================
     LEAD CAPTURE — Lista de espera de alertas + derivación a Paralelo.

     OJO (honestidad del funnel): Tromay NO envía correos hoy — no hay SMTP y
     el servicio de alertas fue dado de baja. Este bloque NO puede prometer
     "te avisaremos" ni una baja de suscripción que no existe. Registra el
     interés y manda al usuario a Paralelo, que muestra el dólar en vivo YA.
     Ver la nota en App\Http\Controllers\LeadController antes de cambiar el copy.
     Uso:  @include('partials.lead-capture')
           @include('partials.lead-capture', ['source' => 'quote'])
     Persiste el email en la tabla `leads` vía POST /leads (fetch + CSRF).
================================================================ --}}
@php $leadSource = $leadSource ?? ($source ?? 'home'); @endphp

<section class="kap-lead-section" style="background:var(--kap-surface);padding:80px 0;">
    <div class="container">
        <div class="kap-lead-card" style="max-width:640px;margin:0 auto;background:var(--kap-black);border:1px solid var(--kap-border);border-radius:16px;padding:40px 32px;text-align:center;">

            <div class="text-center mb-3">
                <span class="section-title"><span>Lista de espera</span></span>
                <h2 class="kap-section-h2" style="margin-top:8px;font-size:26px;">
                    🔔 Anotate para las <span class="kap-hl-green">alertas de tasas</span>
                </h2>
                <p class="kap-section-body--lg" style="max-width:480px;margin:12px auto 0;color:var(--kap-text-muted);">
                    Todavía no enviamos avisos por correo. Dejanos tu email y te sumamos a la
                    lista de espera para cuando habilitemos las alertas.
                    <strong style="color:var(--kap-text);">Mientras tanto, seguí el dólar en vivo en Paralelo.</strong>
                </p>
            </div>

            {{-- Formulario --}}
            <form id="kap-lead-form" method="POST" action="{{ url('/leads') }}" novalidate
                  style="margin-top:24px;">
                @csrf
                <input type="hidden" name="source" value="{{ $leadSource }}">

                {{-- Honeypot anti-spam: oculto a usuarios reales, tentador para bots.
                     Si viene lleno, el servidor responde éxito falso sin guardar. --}}
                <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;">
                    <label for="kap-lead-website">No llenar este campo</label>
                    <input type="text" id="kap-lead-website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="kap-lead-fields" style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;align-items:flex-end;">

                    <div style="flex:1 1 240px;text-align:left;min-width:200px;">
                        <label for="kap-lead-email" style="display:block;font-size:12px;font-weight:600;color:var(--kap-text-muted);margin-bottom:6px;">
                            Tu email
                        </label>
                        <input type="email" id="kap-lead-email" name="email" required
                               autocomplete="email" placeholder="vos@ejemplo.com"
                               style="width:100%;padding:13px 14px;border-radius:10px;border:1px solid var(--kap-border);background:var(--kap-surface);color:var(--kap-text);font-size:15px;">
                    </div>

                    <div style="flex:0 1 150px;text-align:left;min-width:130px;">
                        <label for="kap-lead-currency" style="display:block;font-size:12px;font-weight:600;color:var(--kap-text-muted);margin-bottom:6px;">
                            Divisa <span style="font-weight:400;">(opcional)</span>
                        </label>
                        <select id="kap-lead-currency" name="currency"
                                style="width:100%;padding:13px 14px;border-radius:10px;border:1px solid var(--kap-border);background:var(--kap-surface);color:var(--kap-text);font-size:15px;">
                            <option value="">Cualquiera</option>
                            <option value="usd">Dólar (USD)</option>
                            <option value="eur">Euro (EUR)</option>
                            <option value="brl">Real (BRL)</option>
                            <option value="ars">Peso arg. (ARS)</option>
                            <option value="pen">Sol (PEN)</option>
                            <option value="clp">Peso chi. (CLP)</option>
                        </select>
                    </div>

                    <div style="flex:0 0 auto;">
                        <button type="submit" class="default-btn" id="kap-lead-submit"
                                style="white-space:nowrap;">
                            <span>Anotarme</span>
                        </button>
                    </div>
                </div>

                {{-- Mensaje de confirmación / error inline --}}
                <p id="kap-lead-msg" role="status" aria-live="polite"
                   style="display:none;margin:18px 0 0;font-size:14px;"></p>

                <p style="font-size:11px;color:var(--kap-text-muted);margin:16px 0 0;line-height:1.5;">
                    Guardamos únicamente tu email (y la divisa que elijas) para avisarte si habilitamos
                    las alertas. No enviamos publicidad ni compartimos tus datos.
                    Para borrarlo, escribinos a
                    <a href="mailto:kapitalyabolivia@gmail.com" style="color:var(--kap-text-muted);text-decoration:underline;">kapitalyabolivia@gmail.com</a>.
                    Ver la <a href="{{ route('privacy') }}" style="color:var(--kap-text-muted);text-decoration:underline;">política de privacidad</a>.
                </p>
            </form>

        </div>
    </div>
</section>

<script>
(function () {
    var form = document.getElementById('kap-lead-form');
    if (!form || form.dataset.kapBound) return;
    form.dataset.kapBound = '1';

    var msg    = document.getElementById('kap-lead-msg');
    var btn    = document.getElementById('kap-lead-submit');
    var tokenEl = document.querySelector('meta[name="csrf-token"]');

    function showMsg(text, ok, continueUrl) {
        msg.style.display = 'block';
        msg.style.color = ok ? 'var(--kap-green)' : 'var(--kap-gold)';
        msg.textContent = text;
        if (ok && continueUrl) {
            var a = document.createElement('a');
            a.href = continueUrl;
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            a.textContent = ' Ver el dólar en vivo en Paralelo →';
            a.style.color = 'var(--kap-green)';
            a.style.fontWeight = '700';
            a.style.textDecoration = 'underline';
            msg.appendChild(a);
        }
    }

    form.addEventListener('submit', function (e) {
        // Progressive enhancement: si no hay fetch/CSRF, deja el POST normal.
        if (!window.fetch || !tokenEl) return;
        e.preventDefault();

        btn.disabled = true;
        var original = btn.innerHTML;
        btn.innerHTML = '<span>Enviando…</span>';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': tokenEl.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(form)
        })
        .then(function (r) {
            if (r.status === 429) throw new Error('Demasiados intentos. Probá de nuevo en un minuto.');
            if (r.status === 422) return r.json().then(function (d) {
                var first = d && d.errors ? Object.values(d.errors)[0][0] : 'Revisá tu email.';
                throw new Error(first);
            });
            if (!r.ok) throw new Error('No pudimos registrar tu email. Intentá de nuevo.');
            return r.json();
        })
        .then(function (d) {
            form.reset();
            showMsg(d.message || 'Listo, anotamos tu email.', true, d.continue_url);
        })
        .catch(function (err) {
            showMsg(err.message || 'Ocurrió un error. Intentá de nuevo.', false, null);
        })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = original;
        });
    });
})();
</script>
