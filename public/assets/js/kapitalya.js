/**
 * Kapitalya Platform JS
 * Live rates polling · Bidirectional simulator · UX animations
 */

const Kapitalya = (() => {
    'use strict';

    // ----------------------------------------------------------------
    // State
    // ----------------------------------------------------------------
    let ratesCache   = {};   // { [id]: { id, name, buy, sell } }
    let pollInterval = null;
    let tsInterval   = null;
    let lastUpdated  = null; // Date of last successful API response

    // ----------------------------------------------------------------
    // Input sanitization
    // ----------------------------------------------------------------
    function sanitizeAmount(v) {
        const n = parseFloat(String(v).replace(/[^0-9.]/g, '')) || 0;
        return Math.min(Math.max(n, 0), 100_000_000);
    }

    // ----------------------------------------------------------------
    // Formatters
    // ----------------------------------------------------------------
    const fmt = {
        rate(v, decimals = 4) {
            const n = parseFloat(v);
            return isNaN(n) ? '—' : n.toLocaleString('es-BO', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
            });
        },
        currency(v, decimals = 2) {
            const n = parseFloat(v);
            return isNaN(n) ? '—' : n.toLocaleString('es-BO', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
            });
        },
        relativeTime(date) {
            if (!date) return 'ahora mismo';
            const diff = Math.floor((Date.now() - date.getTime()) / 1000);
            if (diff < 10)  return 'ahora mismo';
            if (diff < 60)  return `hace ${diff} seg`;
            if (diff < 120) return 'hace 1 min';
            return `hace ${Math.floor(diff / 60)} min`;
        },
    };

    // ----------------------------------------------------------------
    // Live Rates API
    // ----------------------------------------------------------------
    async function fetchRates() {
        try {
            const r = await fetch('/api/rates', {
                headers: {
                    'Accept':           'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
                signal: AbortSignal.timeout(8000),
            });
            if (!r.ok) throw new Error(`HTTP ${r.status}`);
            return await r.json();
        } catch {
            return null;
        }
    }

    // Animate a rate value change and update its trend arrow
    function animateValue(el, newVal) {
        const old  = parseFloat(el.textContent?.replace(/[^0-9.]/g, '')) || 0;
        const next = parseFloat(newVal) || 0;
        if (old === next) return;

        el.classList.remove('kap-flash-up', 'kap-flash-down');
        void el.offsetWidth;
        el.classList.add(next > old ? 'kap-flash-up' : 'kap-flash-down');
        el.textContent = fmt.rate(next);

        const row = el.closest('[data-kap-id]');
        if (row) {
            const isBuy = el.hasAttribute('data-kap-buy');
            const trend = row.querySelector(isBuy ? '[data-kap-trend-buy]' : '[data-kap-trend-sell]');
            if (trend) {
                trend.className = 'kap-trend ' + (next > old ? 'up' : 'down');
                trend.textContent = next > old ? '▲' : '▼';
            }
        }
    }

    // Estados honestos del badge, alineados con RateService::rate_source.
    const BADGE_STATE = {
        forex: { text: 'En vivo',     cache: false },
        cache: { text: 'Caché',       cache: true  },
        seed:  { text: 'Referencial', cache: true  },
    };

    function applyBadge(id, source) {
        const state = BADGE_STATE[source] || BADGE_STATE.seed;

        document.querySelectorAll(`[data-kap-badge="${id}"]`).forEach(el => {
            if (el.textContent.trim() !== state.text) el.textContent = state.text;
            el.classList.toggle('kap-live-badge--cache', state.cache);
        });
    }

    function applyRatesToDOM(payload) {
        const rates = payload?.data ?? payload; // handle {data:[...]} wrapper or raw array
        if (!Array.isArray(rates)) return;

        rates.forEach(rate => {
            const id = String(rate.id);

            document.querySelectorAll(`[data-kap-id="${id}"]`).forEach(row => {
                const buyEl  = row.querySelector('[data-kap-buy]');
                const sellEl = row.querySelector('[data-kap-sell]');
                if (buyEl)  animateValue(buyEl,  rate.buy);
                if (sellEl) animateValue(sellEl, rate.sell);
            });

            document.querySelectorAll(`#sim-currency option[value="${id}"]`).forEach(opt => {
                opt.dataset.buy  = rate.buy;
                opt.dataset.sell = rate.sell;
            });

            const tb = document.querySelector(`[data-tick-buy="${id}"]`);
            const ts = document.querySelector(`[data-tick-sell="${id}"]`);
            if (tb) tb.textContent = fmt.rate(rate.buy);
            if (ts) ts.textContent = fmt.rate(rate.sell);

            // El badge de frescura tiene que seguir a la tasa. Antes solo se
            // pintaba en el servidor: si forex se caía a mitad de sesión, los
            // números cambiaban al último-conocido pero el badge seguía diciendo
            // "En vivo" — justo la afirmación que el backend se cuida de no hacer.
            applyBadge(id, rate.rate_source);

            ratesCache[id] = rate;
        });

        lastUpdated = new Date();
        updateTimestampDOM();
        calculateSim();
    }

    async function initLiveRates() {
        if (Array.isArray(window.__kapRates)) {
            window.__kapRates.forEach(r => { ratesCache[String(r.id)] = r; });
        }

        const fresh = await fetchRates();
        if (fresh) applyRatesToDOM(fresh);

        pollInterval = setInterval(async () => {
            const updated = await fetchRates();
            if (updated) applyRatesToDOM(updated);
        }, 60_000);

        // Refresh the "Actualizado hace X min" text every 20 seconds
        tsInterval = setInterval(updateTimestampDOM, 20_000);
    }

    // ----------------------------------------------------------------
    // Timestamp — "Actualizado hace X min"
    // ----------------------------------------------------------------
    function updateTimestampDOM() {
        document.querySelectorAll('[data-kap-timestamp]').forEach(el => {
            el.textContent = `Actualizado ${fmt.relativeTime(lastUpdated)}`;
        });
    }

    // ----------------------------------------------------------------
    // Simulator — bidirectional
    // ----------------------------------------------------------------
    function getRate(currencyId, type) {
        const id = String(currencyId);

        const cached = ratesCache[id];
        if (cached) return parseFloat(type === 'buy' ? cached.buy : cached.sell) || 0;

        const opt = document.querySelector(`#sim-currency option[value="${id}"]`);
        if (opt?.dataset[type]) return parseFloat(opt.dataset[type]) || 0;

        const row = document.querySelector(`[data-kap-id="${id}"]`);
        if (row) {
            const el = row.querySelector(`[data-kap-${type}]`);
            return parseFloat(el?.textContent) || 0;
        }

        return 0;
    }

    function calculateSim() {
        const form = document.getElementById('kap-simulator');
        if (!form) return;

        const activeTab  = form.querySelector('.kap-tab-btn.active');
        const type       = activeTab?.dataset.type ?? 'buy';
        const amount     = sanitizeAmount(form.querySelector('#sim-amount')?.value ?? '');
        const currencyId = form.querySelector('#sim-currency')?.value ?? '';
        const resultWrap = document.getElementById('sim-result');
        if (!resultWrap) return;

        if (!amount || !currencyId) {
            resultWrap.style.display = 'none';
            return;
        }

        const buyRate  = getRate(currencyId, 'buy');
        const sellRate = getRate(currencyId, 'sell');
        const rate     = type === 'buy' ? buyRate : sellRate;

        if (!rate) {
            resultWrap.style.display = 'none';
            return;
        }

        const isBuy        = type === 'buy';
        const currencyName = (form.querySelector(`#sim-currency option[value="${currencyId}"]`)
                                 ?.text?.split(' — ')[0] ?? 'Divisa').toUpperCase();

        // Primary result
        const primary = amount * rate;

        // Inverse result — "Para recibir X, necesitás Y"
        const inverseRate   = isBuy ? sellRate : buyRate;
        const inverseLabel  = isBuy ? 'Para obtener esa divisa en sentido venta' : 'Precio de compra de esa divisa';
        const inverseResult = inverseRate > 0 ? (amount * inverseRate) : null;

        const mainColor = `var(--kap-${isBuy ? 'green' : 'gold'})`;
        const fromLabel = isBuy ? 'BOB'        : currencyName;
        const toLabel   = isBuy ? currencyName : 'BOB';

        // CTA WhatsApp con la operación pre-rellenada — convierte el simulador
        // (que solo calculaba) en un cierre de lead hacia la sucursal.
        const waMsg = `Hola Tromay, quiero ${isBuy ? 'comprar' : 'vender'} ${currencyName}. `
            + `Simulé ${fmt.currency(amount)} ${fromLabel} → ${fmt.currency(primary)} ${toLabel} `
            + `a tasa ${fmt.rate(rate)}. ¿Me confirman la operación?`;
        const waUrl = 'https://api.whatsapp.com/send?phone=59164082967&text=' + encodeURIComponent(waMsg);

        resultWrap.innerHTML = `
            <div class="kap-sim-result" style="display:block;">
                <div class="kap-sim-result-row">
                    <span class="kap-sim-result-label">Monto ingresado</span>
                    <span class="kap-sim-result-value">${fmt.currency(amount)} ${fromLabel}</span>
                </div>
                <div class="kap-sim-result-row">
                    <span class="kap-sim-result-label">Tasa de ${isBuy ? 'compra' : 'venta'}</span>
                    <span class="kap-sim-result-value" style="color:${mainColor}">${fmt.rate(rate)}</span>
                </div>
                <div class="kap-sim-result-row" style="border-top:1px solid var(--kap-border);padding-top:10px;margin-top:4px;">
                    <span class="kap-sim-result-label"><strong>Recibirá aproximadamente</strong></span>
                    <span class="kap-sim-result-value total" style="color:${mainColor};font-size:18px;">
                        ${fmt.currency(primary)} ${toLabel}
                    </span>
                </div>
                ${inverseResult !== null ? `
                <div class="kap-sim-result-row" style="margin-top:12px;padding-top:10px;border-top:1px solid var(--kap-border);opacity:0.7;">
                    <span class="kap-sim-result-label" style="font-size:11px;">${inverseLabel}</span>
                    <span class="kap-sim-result-value" style="font-size:13px;">
                        ${fmt.currency(inverseResult)} ${toLabel}
                    </span>
                </div>` : ''}
            </div>
            <a href="${waUrl}" target="_blank" rel="noopener" class="kap-sim-wa" data-kap-cta="sim-whatsapp"
               style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:14px;padding:13px 18px;background:#25D366;color:#fff;font-weight:700;font-size:14px;border-radius:10px;text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.124.557 4.118 1.531 5.845L.057 23.945l6.272-1.648A11.934 11.934 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.794 9.794 0 01-5.031-1.388l-.361-.214-3.722.977.995-3.634-.235-.374A9.818 9.818 0 012.182 12C2.182 6.57 6.57 2.182 12 2.182S21.818 6.57 21.818 12 17.43 21.818 12 21.818z"/></svg>
                Confirmá esta operación por WhatsApp
            </a>
            <p class="kap-sim-disclaimer">* Estimado referencial. La tasa final se confirma en sucursal.</p>
        `;
        resultWrap.style.display = 'block';

        const resultEl = resultWrap.querySelector('.kap-sim-result');
        if (resultEl) {
            resultEl.classList.remove('kap-result-pulse');
            void resultEl.offsetWidth;
            resultEl.classList.add('kap-result-pulse');
        }
    }

    function initSimulator() {
        const form = document.getElementById('kap-simulator');
        if (!form) return;

        form.querySelectorAll('.kap-tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                form.querySelectorAll('.kap-tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                // Update label
                const amountLabel = form.querySelector('label[for="sim-amount"]');
                if (amountLabel) {
                    amountLabel.textContent = btn.dataset.type === 'buy'
                        ? 'Monto a entregar (BOB)'
                        : 'Monto en divisa a vender';
                }
                calculateSim();
            });
        });

        form.querySelector('#sim-amount')?.addEventListener('input', calculateSim);
        form.querySelector('#sim-currency')?.addEventListener('change', calculateSim);
        form.addEventListener('submit', e => e.preventDefault());
    }

    // ----------------------------------------------------------------
    // Open / Closed status — based on La Paz local time (UTC-4)
    // Hours: Mon-Sat 08:00-19:00, Sun 08:00-13:00
    // ----------------------------------------------------------------
    function isOpen(date) {
        // Convert to Bolivia time (UTC-4, no DST)
        const utc = date.getTime() + date.getTimezoneOffset() * 60_000;
        const local = new Date(utc - 4 * 3_600_000);
        const day  = local.getDay();   // 0=Sun, 6=Sat
        const hour = local.getHours();
        const min  = local.getMinutes();
        const hhmm = hour * 60 + min;

        if (day === 0) return hhmm >= 8 * 60 && hhmm < 13 * 60;          // Sun
        if (day >= 1 && day <= 6) return hhmm >= 8 * 60 && hhmm < 19 * 60; // Mon-Sat
        return false;
    }

    function initOpenStatus() {
        const badges = document.querySelectorAll('#kap-open-status');
        const hint   = document.querySelector('#kap-open-hours-hint');
        if (!badges.length) return;

        const open = isOpen(new Date());

        badges.forEach(badge => {
            badge.className = 'kap-status-badge kap-status-badge--' + (open ? 'open' : 'closed');
            badge.textContent = open ? 'Abierto ahora' : 'Cerrado';
        });

        if (hint) {
            const now   = new Date();
            const utc   = now.getTime() + now.getTimezoneOffset() * 60_000;
            const local = new Date(utc - 4 * 3_600_000);
            const day   = local.getDay();

            if (open) {
                const closeHour = day === 0 ? 13 : 19;
                hint.textContent = `· Cierra a las ${closeHour}:00`;
            } else {
                hint.textContent = '· Abre a las 08:00';
            }
        }
    }

    // ----------------------------------------------------------------
    // Ticker
    // ----------------------------------------------------------------
    function initTicker() {
        const track = document.querySelector('.kap-ticker-track');
        if (!track || !track.children.length) return;
        [...track.children].forEach(el => track.appendChild(el.cloneNode(true)));
    }

    // ----------------------------------------------------------------
    // Scroll animations
    // ----------------------------------------------------------------
    function initScrollAnimations() {
        if (!('IntersectionObserver' in window)) {
            document.querySelectorAll('.kap-fade-up').forEach(el => el.classList.add('is-visible'));
            return;
        }
        const io = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.10 });
        document.querySelectorAll('.kap-fade-up').forEach(el => io.observe(el));
    }

    // ----------------------------------------------------------------
    // Hero counters
    // ----------------------------------------------------------------
    function initCounters() {
        if (!('IntersectionObserver' in window)) return;
        const counters = document.querySelectorAll('[data-kap-count]');
        if (!counters.length) return;

        const io = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el       = entry.target;
                const target   = parseFloat(el.dataset.kapCount) || 0;
                const suffix   = el.dataset.suffix ?? '';
                const duration = 1600;
                const start    = performance.now();

                const tick = now => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased    = 1 - Math.pow(1 - progress, 3);
                    el.textContent = fmt.currency(target * eased, 0) + suffix;
                    if (progress < 1) requestAnimationFrame(tick);
                };

                requestAnimationFrame(tick);
                io.unobserve(el);
            });
        }, { threshold: 0.5 });

        counters.forEach(el => io.observe(el));
    }

    // ----------------------------------------------------------------
    // Public init
    // ----------------------------------------------------------------
    function init() {
        initOpenStatus();
        initTicker();
        initSimulator();
        initScrollAnimations();
        initCounters();
        initLiveRates();
    }

    return { init, fetchRates, fmt, calculate: calculateSim };
})();

document.addEventListener('DOMContentLoaded', () => Kapitalya.init());
