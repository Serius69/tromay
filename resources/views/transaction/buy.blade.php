@extends('layout.masteradmin')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('body')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Compra de Divisa</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Admin</a></li>
                                <li class="breadcrumb-item active">Compra</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xxl-7 col-xl-8 col-lg-10">

                    {{-- Alert container --}}
                    <div id="tx-alert" class="d-none"></div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <span class="badge bg-success me-2 fs-13">COMPRA</span>
                            <h5 class="card-title mb-0">El cliente entrega divisa — recibe BOB</h5>
                        </div>
                        <div class="card-body">

                            {{-- ── CLIENT SECTION ─────────────────────────────── --}}
                            <h6 class="fw-semibold text-uppercase text-muted fs-11 mb-3">Datos del cliente</h6>

                            <div class="row g-3 mb-4">
                                {{-- CI with autocomplete --}}
                                <div class="col-md-4">
                                    <label class="form-label" for="ci">CI <span class="text-danger">*</span></label>
                                    <div class="position-relative">
                                        <input type="text" id="ci" name="ci" class="form-control"
                                            placeholder="Buscar por CI o nombre…" autocomplete="off" required>
                                        <div id="ci-suggestions"
                                            class="position-absolute bg-white border rounded shadow-sm w-100 d-none"
                                            style="z-index:1050;max-height:200px;overflow-y:auto;top:100%;left:0;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="name">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Nombre" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="lastname">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Apellido" required>
                                </div>
                            </div>

                            <hr class="mb-4">

                            {{-- ── TRANSACTION SECTION ─────────────────────────── --}}
                            <h6 class="fw-semibold text-uppercase text-muted fs-11 mb-3">Datos de la operación</h6>

                            <div class="row g-3 mb-3">
                                {{-- Foreign currency selector (cash1 for SELL-type calculation) --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="cash1_id">
                                        Divisa que entrega el cliente <span class="text-danger">*</span>
                                    </label>
                                    <select id="cash1_id" name="cash1_id" class="form-select" required>
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($cashes as $cash)
                                        <option value="{{ $cash->id }}"
                                            data-buy="{{ $cash->buy }}"
                                            data-sell="{{ $cash->sell }}"
                                            data-name="{{ strtoupper($cash->getRawOriginal('name')) }}">
                                            {{ strtoupper($cash->getRawOriginal('name')) }}
                                            &mdash; Compra: {{ number_format($cash->buy, 4) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Destination currency (cash2) --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="cash2_id">
                                        Divisa que recibe el cliente <span class="text-danger">*</span>
                                    </label>
                                    <select id="cash2_id" name="cash2_id" class="form-select" required>
                                        <option value="">-- Seleccionar --</option>
                                        @foreach($cashes as $cash)
                                        <option value="{{ $cash->id }}"
                                            data-name="{{ strtoupper($cash->getRawOriginal('name')) }}">
                                            {{ strtoupper($cash->getRawOriginal('name')) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Amount --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="amount1">
                                        Monto en divisa <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="amount1" name="amount1" class="form-control"
                                        placeholder="0.00" min="0.01" step="0.01" required>
                                </div>

                                {{-- Date --}}
                                <div class="col-md-6">
                                    <label class="form-label" for="date">Fecha <span class="text-danger">*</span></label>
                                    <input type="date" id="date" name="date" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            {{-- Live rate + preview --}}
                            <div id="rate-preview" class="alert alert-soft-success border-success d-none mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted fs-12">Tasa de compra</span>
                                        <div class="fw-semibold fs-15" id="preview-rate">—</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted fs-12">El cliente recibirá aprox.</span>
                                        <div class="fw-bold fs-18 text-success" id="preview-amount2">—</div>
                                    </div>
                                </div>
                                <p class="mb-0 mt-2 text-muted fs-11">* Estimado referencial. La tasa final se confirma al procesar.</p>
                            </div>

                            {{-- Submit --}}
                            <div class="text-end">
                                <button type="button" id="btn-open-confirm" class="btn btn-success btn-lg px-5" disabled>
                                    <i class="ri-check-double-line me-1"></i>Confirmar transacción
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Confirmation modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Confirmar operación de compra</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirm-body">
                {{-- filled by JS --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btn-confirm-submit">
                    <span id="confirm-spinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                    Procesar compra
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
(function () {
    'use strict';

    const STORE_URL   = '{{ route("admin.transaction.store") }}';
    const SEARCH_URL  = '{{ route("admin.client.search") }}';
    const CSRF        = document.querySelector('meta[name="csrf-token"]').content;
    const TX_TYPE     = 2; // TYPE_SELL: client sells foreign currency, receives BOB

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------
    const fmt = (n, d = 2) => Number(n).toLocaleString('es-BO', {
        minimumFractionDigits: d, maximumFractionDigits: d
    });
    const esc = s => String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

    // ----------------------------------------------------------------
    // CI Autocomplete
    // ----------------------------------------------------------------
    let ciTimer = null;
    const ciInput   = document.getElementById('ci');
    const ciSuggest = document.getElementById('ci-suggestions');

    ciInput.addEventListener('input', function () {
        clearTimeout(ciTimer);
        const q = this.value.trim();
        if (q.length < 2) { ciSuggest.classList.add('d-none'); return; }
        ciTimer = setTimeout(() => fetchClients(q), 300);
    });

    async function fetchClients(q) {
        try {
            const res   = await fetch(SEARCH_URL + '?q=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const items = await res.json();
            renderSuggestions(items);
        } catch { ciSuggest.classList.add('d-none'); }
    }

    function renderSuggestions(items) {
        if (!items.length) { ciSuggest.classList.add('d-none'); return; }
        ciSuggest.innerHTML = items.map(c =>
            `<div class="px-3 py-2 border-bottom ci-option" style="cursor:pointer;"
                  data-ci="${esc(c.ci)}" data-name="${esc(c.name)}" data-lastname="${esc(c.lastname)}">
                <strong>${esc(c.ci)}</strong>
                <span class="text-muted ms-2">${esc(c.name)} ${esc(c.lastname)}</span>
            </div>`
        ).join('');
        ciSuggest.classList.remove('d-none');
    }

    ciSuggest.addEventListener('click', function (e) {
        const opt = e.target.closest('.ci-option');
        if (!opt) return;
        document.getElementById('ci').value       = opt.dataset.ci;
        document.getElementById('name').value     = opt.dataset.name;
        document.getElementById('lastname').value = opt.dataset.lastname;
        ciSuggest.classList.add('d-none');
    });

    document.addEventListener('click', function (e) {
        if (!ciInput.contains(e.target) && !ciSuggest.contains(e.target)) {
            ciSuggest.classList.add('d-none');
        }
    });

    // ----------------------------------------------------------------
    // Rate preview
    // ----------------------------------------------------------------
    function updatePreview() {
        const cash1Opt = document.getElementById('cash1_id').selectedOptions[0];
        const amount1  = parseFloat(document.getElementById('amount1').value) || 0;

        const rateVal  = cash1Opt ? parseFloat(cash1Opt.dataset.sell) || 0 : 0;
        const currency = cash1Opt ? cash1Opt.dataset.name : '';

        const preview = document.getElementById('rate-preview');
        const btnOpen = document.getElementById('btn-open-confirm');

        if (rateVal > 0 && amount1 > 0 && currency) {
            const amount2 = amount1 * rateVal;
            document.getElementById('preview-rate').textContent =
                '1 ' + currency + ' = ' + fmt(rateVal, 4) + ' BOB';
            document.getElementById('preview-amount2').textContent =
                'Bs ' + fmt(amount2, 2) + ' BOB';
            preview.classList.remove('d-none');
            btnOpen.disabled = false;
        } else {
            preview.classList.add('d-none');
            btnOpen.disabled = true;
        }
    }

    document.getElementById('cash1_id').addEventListener('change', updatePreview);
    document.getElementById('amount1').addEventListener('input', updatePreview);

    // ----------------------------------------------------------------
    // Confirmation modal
    // ----------------------------------------------------------------
    document.getElementById('btn-open-confirm').addEventListener('click', function () {
        const ci       = document.getElementById('ci').value.trim();
        const name     = document.getElementById('name').value.trim();
        const lastname = document.getElementById('lastname').value.trim();
        const cash1Opt = document.getElementById('cash1_id').selectedOptions[0];
        const cash2Opt = document.getElementById('cash2_id').selectedOptions[0];
        const amount1  = parseFloat(document.getElementById('amount1').value) || 0;
        const date     = document.getElementById('date').value;
        const rateVal  = cash1Opt ? parseFloat(cash1Opt.dataset.sell) || 0 : 0;
        const amount2  = rateVal > 0 ? amount1 * rateVal : 0;

        document.getElementById('confirm-body').innerHTML = `
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><th class="text-muted fw-normal" style="width:45%">Cliente CI</th><td><strong>${esc(ci)}</strong></td></tr>
                        <tr><th class="text-muted fw-normal">Nombre</th><td>${esc(name)} ${esc(lastname)}</td></tr>
                        <tr><th class="text-muted fw-normal">Divisa entregada</th><td>${esc(cash1Opt?.dataset.name ?? '—')}</td></tr>
                        <tr><th class="text-muted fw-normal">Divisa recibida</th><td>${esc(cash2Opt?.dataset.name ?? '—')}</td></tr>
                        <tr><th class="text-muted fw-normal">Monto en divisa</th><td><strong>${fmt(amount1, 2)}</strong></td></tr>
                        <tr><th class="text-muted fw-normal">Tasa compra</th><td>${fmt(rateVal, 4)}</td></tr>
                        <tr class="table-success"><th class="fw-semibold">Monto en BOB (aprox.)</th><td><strong>Bs ${fmt(amount2, 2)}</strong></td></tr>
                        <tr><th class="text-muted fw-normal">Fecha</th><td>${esc(date)}</td></tr>
                    </tbody>
                </table>
            </div>`;

        bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmModal')).show();
    });

    // ----------------------------------------------------------------
    // Submit
    // ----------------------------------------------------------------
    document.getElementById('btn-confirm-submit').addEventListener('click', async function () {
        const spinner = document.getElementById('confirm-spinner');
        this.disabled = true;
        spinner.classList.remove('d-none');

        const body = new URLSearchParams({
            _token:    CSRF,
            ci:        document.getElementById('ci').value.trim(),
            name:      document.getElementById('name').value.trim(),
            lastname:  document.getElementById('lastname').value.trim(),
            cash1_id:  document.getElementById('cash1_id').value,
            cash2_id:  document.getElementById('cash2_id').value,
            amount1:   document.getElementById('amount1').value,
            date:      document.getElementById('date').value,
            type:      TX_TYPE,
        });

        try {
            const res  = await fetch(STORE_URL, {
                method:  'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    body.toString(),
            });
            const json = await res.json();

            bootstrap.Modal.getOrCreateInstance(document.getElementById('confirmModal')).hide();

            if (res.ok) {
                showAlert('success', '<i class="ri-checkbox-circle-line me-2"></i>' + (json.success ?? 'Transacción registrada.'));
                document.querySelectorAll('#ci,#name,#lastname,#cash1_id,#cash2_id,#amount1').forEach(el => {
                    el.tagName === 'SELECT' ? el.selectedIndex = 0 : (el.value = '');
                });
                document.getElementById('date').value = new Date().toISOString().split('T')[0];
                document.getElementById('rate-preview').classList.add('d-none');
                document.getElementById('btn-open-confirm').disabled = true;
            } else {
                const errors = json.errors ? Object.values(json.errors).flat().join('<br>') : (json.message ?? 'Error al registrar.');
                showAlert('danger', '<i class="ri-error-warning-line me-2"></i>' + errors);
            }
        } catch (err) {
            showAlert('danger', '<i class="ri-error-warning-line me-2"></i>Error de red. Intenta de nuevo.');
        } finally {
            this.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    function showAlert(type, html) {
        const el = document.getElementById('tx-alert');
        el.className = 'alert alert-' + type + ' alert-dismissible';
        el.innerHTML = html + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        el.classList.remove('d-none');
        el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

})();
</script>
@endsection
