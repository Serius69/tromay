/**
 * Kapitalya Platform JS
 * Live rates polling · Intelligent simulator · UX animations
 */

const Kapitalya = (() => {
    'use strict';

    // ----------------------------------------------------------------
    // State
    // ----------------------------------------------------------------
    let ratesCache = {};   // { [id]: { id, name, buy, sell } }
    let pollInterval = null;

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
        void el.offsetWidth; // reflow to restart animation
        el.classList.add(next > old ? 'kap-flash-up' : 'kap-flash-down');
        el.textContent = fmt.rate(next);

        // Update trend arrow in same rate row
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

    function applyRatesToDOM(rates) {
        rates.forEach(rate => {
            const id = String(rate.id);

            // Update every rate row for this currency
            document.querySelectorAll(`[data-kap-id="${id}"]`).forEach(row => {
                const buyEl  = row.querySelector('[data-kap-buy]');
                const sellEl = row.querySelector('[data-kap-sell]');
                if (buyEl)  animateValue(buyEl,  rate.buy);
                if (sellEl) animateValue(sellEl, rate.sell);
            });

            // Keep simulator options fresh so getRate() works without DOM rows
            document.querySelectorAll(`#sim-currency option[value="${id}"]`).forEach(opt => {
                opt.dataset.buy  = rate.buy;
                opt.dataset.sell = rate.sell;
            });

            // Update ticker
            const tb = document.querySelector(`[data-tick-buy="${id}"]`);
            const ts = document.querySelector(`[data-tick-sell="${id}"]`);
            if (tb) tb.textContent = fmt.rate(rate.buy);
            if (ts) ts.textContent = fmt.rate(rate.sell);

            // Persist in cache
            ratesCache[id] = rate;
        });

        // Auto-refresh open simulator
        calculateSim();
    }

    async function initLiveRates() {
        // Seed cache from any server-rendered inline data (e.g. cash/show page)
        if (Array.isArray(window.__kapRates)) {
            window.__kapRates.forEach(r => { ratesCache[String(r.id)] = r; });
        }

        const fresh = await fetchRates();
        if (fresh) applyRatesToDOM(fresh);

        pollInterval = setInterval(async () => {
            const updated = await fetchRates();
            if (updated) applyRatesToDOM(updated);
        }, 60_000);
    }

    // ----------------------------------------------------------------
    // Simulator
    // ----------------------------------------------------------------

    // Rate resolution order: cache → option dataset → DOM rate row
    function getRate(currencyId, type) {
        const id = String(currencyId);

        // 1. In-memory cache (populated by API poll)
        const cached = ratesCache[id];
        if (cached) return parseFloat(type === 'buy' ? cached.buy : cached.sell) || 0;

        // 2. Simulator option dataset (set server-side or updated by API)
        const opt = document.querySelector(`#sim-currency option[value="${id}"]`);
        if (opt?.dataset[type]) return parseFloat(opt.dataset[type]) || 0;

        // 3. Rendered rate row in DOM
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

        const rate = getRate(currencyId, type);
        if (!rate) {
            resultWrap.style.display = 'none';
            return;
        }

        const result       = amount * rate;
        const isBuy        = type === 'buy';
        const currencyName = form.querySelector(`#sim-currency option[value="${currencyId}"]`)
                                 ?.text?.split(' — ')[0] ?? 'Divisa';
        const fromLabel    = isBuy ? 'BOB'        : currencyName;
        const toLabel      = isBuy ? currencyName : 'BOB';
        const rateColor    = `var(--kap-${isBuy ? 'green' : 'gold'})`;
        const totalClass   = isBuy ? '' : 'sell-total';

        resultWrap.innerHTML = `
            <div class="kap-sim-result" style="display:block;">
                <div class="kap-sim-result-row">
                    <span class="kap-sim-result-label">Monto ingresado</span>
                    <span class="kap-sim-result-value">${fmt.currency(amount)} ${fromLabel}</span>
                </div>
                <div class="kap-sim-result-row">
                    <span class="kap-sim-result-label">Tasa de ${isBuy ? 'compra' : 'venta'}</span>
                    <span class="kap-sim-result-value" style="color:${rateColor}">${fmt.rate(rate)}</span>
                </div>
                <div class="kap-sim-result-row">
                    <span class="kap-sim-result-label"><strong>Recibirá aproximadamente</strong></span>
                    <span class="kap-sim-result-value total ${totalClass}">${fmt.currency(result)} ${toLabel}</span>
                </div>
            </div>
            <p class="kap-sim-disclaimer">* Estimado referencial. La tasa final se confirma en sucursal.</p>
        `;
        resultWrap.style.display = 'block';

        // Pulse the result to signal live update
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
                calculateSim();
            });
        });

        form.querySelector('#sim-amount')?.addEventListener('input',  calculateSim);
        form.querySelector('#sim-currency')?.addEventListener('change', calculateSim);
        form.addEventListener('submit', e => e.preventDefault());
    }

    // ----------------------------------------------------------------
    // Ticker — duplicate items for seamless infinite scroll
    // ----------------------------------------------------------------
    function initTicker() {
        const track = document.querySelector('.kap-ticker-track');
        if (!track || !track.children.length) return;
        [...track.children].forEach(el => track.appendChild(el.cloneNode(true)));
    }

    // ----------------------------------------------------------------
    // Scroll-triggered fade-in
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
    // Hero counter animation
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
        initTicker();
        initSimulator();
        initScrollAnimations();
        initCounters();
        initLiveRates();
    }

    return { init, fetchRates, fmt, calculate: calculateSim };
})();

document.addEventListener('DOMContentLoaded', () => Kapitalya.init());
