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

        // "Compro" (visitante compra divisa): la casa VENDE → tasa de venta,
        //   entra BOB y la divisa se obtiene dividiendo.
        // "Vendo" (visitante vende divisa): la casa COMPRA → tasa de compra,
        //   entra divisa y los BOB se obtienen multiplicando.
        const isBuy = type === 'buy';
        const rate  = isBuy ? sellRate : buyRate;

        if (!rate) {
            resultWrap.style.display = 'none';
            return;
        }

        const currencyName = (form.querySelector(`#sim-currency option[value="${currencyId}"]`)
                                 ?.text?.split(' — ')[0] ?? 'Divisa').toUpperCase();

        // Primary result
        const primary = isBuy ? (amount / rate) : (amount * rate);

        const mainColor = `var(--kap-${isBuy ? 'green' : 'gold'})`;
        const fromLabel = isBuy ? 'BOB'        : currencyName;
        const toLabel   = isBuy ? currencyName : 'BOB';

        resultWrap.innerHTML = `
            <div class="kap-sim-result" style="display:block;">
                <div class="kap-sim-result-row">
                    <span class="kap-sim-result-label">Monto ingresado</span>
                    <span class="kap-sim-result-value">${fmt.currency(amount)} ${fromLabel}</span>
                </div>
                <div class="kap-sim-result-row">
                    <span class="kap-sim-result-label">Tasa de ${isBuy ? 'venta' : 'compra'}</span>
                    <span class="kap-sim-result-value" style="color:${mainColor}">${fmt.rate(rate)}</span>
                </div>
                <div class="kap-sim-result-row" style="border-top:1px solid var(--kap-border);padding-top:10px;margin-top:4px;">
                    <span class="kap-sim-result-label"><strong>Recibirá aproximadamente</strong></span>
                    <span class="kap-sim-result-value total" style="color:${mainColor};font-size:18px;">
                        ${fmt.currency(primary)} ${toLabel}
                    </span>
                </div>
            </div>
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
