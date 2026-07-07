
@extends('layout.masteradmin')

@section('token')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('body')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">ADMIN COTIZACIONES</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ url('admin') }}">INICIO ADMIN</a></li>
                                    <li class="breadcrumb-item active">COTIZACIONES</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Cotizaciones / Proformas de divisa</h4>
                            </div>
                            <div class="card-body">
                                <div id="quotationList">
                                    <div class="row g-4 mb-3">
                                        <div class="col-sm-auto">
                                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="createNew" data-bs-target="#showModal">
                                                <i class="ri-add-line align-bottom me-1"></i> Nueva cotización
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive table-card mt-3 mb-1">
                                        <table id="DataTables_Table_Quotation" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 10px;">#</th>
                                                    <th>Divisa</th>
                                                    <th>Cliente</th>
                                                    <th>Tipo</th>
                                                    <th>Monto</th>
                                                    <th>Tasa</th>
                                                    <th>Total (BOB)</th>
                                                    <th>Válida hasta</th>
                                                    <th>Estado</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- POP UP FORM --}}
                <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="modelHeading" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modelHeading">Nueva cotización</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>

                            <form id="Form" name="Form">
                                <div class="modal-body">
                                    <div id="form-errors" class="alert alert-danger d-none"></div>

                                    <input type="hidden" id="id" name="id" />

                                    <div class="mb-3">
                                        <label for="cash_id" class="form-label">Divisa</label>
                                        <select id="cash_id" name="cash_id" class="form-select" required>
                                            <option value="">Seleccione divisa…</option>
                                            @foreach($cashes as $cash)
                                                <option value="{{ $cash->id }}"
                                                        data-buy="{{ $cash->buy }}"
                                                        data-sell="{{ $cash->sell }}">
                                                    {{ strtoupper($cash->getRawOriginal('name')) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">Cliente (opcional)</label>
                                        <select id="client_id" name="client_id" class="form-select">
                                            <option value="">Sin cliente</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">
                                                    {{ $client->ci }} — {{ $client->name }} {{ $client->lastname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="type" class="form-label">Tipo de operación</label>
                                        <select id="type" name="type" class="form-select" required>
                                            <option value="buy">Compra (la casa compra divisa)</option>
                                            <option value="sell">Venta (la casa vende divisa)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Monto en divisa</label>
                                        <input type="number" step="0.01" min="0.01" id="amount" name="amount" class="form-control" placeholder="0.00" required />
                                    </div>

                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Tasa aplicada</label>
                                            <input type="text" id="rate_preview" class="form-control" readonly />
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Total estimado (BOB)</label>
                                            <input type="text" id="total_preview" class="form-control" readonly />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="valid_until" class="form-label">Válida hasta</label>
                                        <input type="date" id="valid_until" name="valid_until" class="form-control" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notas (opcional)</label>
                                        <textarea id="notes" name="notes" class="form-control" rows="2" maxlength="500"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Estado</label>
                                        <select id="status" name="status" class="form-select">
                                            <option value="1">Vigente</option>
                                            <option value="0">Anulada</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-success" id="saveBtn">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- DELETE MODAL --}}
                <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="mt-2">
                                    <h4>¿Está seguro?</h4>
                                    <p class="text-muted mb-0">¿Desea eliminar esta cotización?</p>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn w-sm btn-danger" id="delete-record">Sí, eliminar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONVERT MODAL --}}
                <div class="modal fade zoomIn" id="convertRecordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="mt-2">
                                    <h4>Convertir en transacción</h4>
                                    <p class="text-muted mb-0">
                                        Se registrará una transacción con la tasa pactada en la cotización
                                        y la cotización quedará marcada como convertida. Esta acción no se puede deshacer.
                                    </p>
                                    <div id="convert-errors" class="alert alert-danger d-none mt-3 mb-0 text-start"></div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn w-sm btn-success" id="convert-record">Sí, convertir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var baseUrl = "{{ route('admin.quotation.index') }}";
    var deleteId = null;
    var convertId = null;

    var table = $('#DataTables_Table_Quotation').DataTable({
        processing: true,
        serverSide: true,
        ajax: baseUrl,
        columns: [
            { data: 'DT_RowIndex',  name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'currency',     name: 'cash_id' },
            { data: 'client_name',  name: 'client_id', orderable: false },
            { data: 'type',         name: 'type' },
            { data: 'amount',       name: 'amount' },
            { data: 'rate',         name: 'rate' },
            { data: 'total',        name: 'total' },
            { data: 'valid_until',  name: 'valid_until' },
            { data: 'status_label', name: 'status' },
            { data: 'action',       name: 'action', orderable: false, searchable: false },
        ]
    });

    // Vista previa de tasa + total
    function refreshPreview() {
        var opt   = $('#cash_id').find('option:selected');
        var type  = $('#type').val();
        var amt   = parseFloat($('#amount').val()) || 0;
        var rate  = parseFloat(type === 'buy' ? opt.data('buy') : opt.data('sell')) || 0;
        $('#rate_preview').val(rate ? rate.toFixed(4) : '');
        $('#total_preview').val(rate && amt ? (amt * rate).toFixed(2) : '');
    }
    $('#cash_id, #type, #amount').on('input change', refreshPreview);

    $('#createNew').click(function () {
        $('#Form').trigger('reset');
        $('#id').val('');
        $('#form-errors').addClass('d-none').empty();
        $('#rate_preview, #total_preview').val('');
        $('#modelHeading').text('Nueva cotización');
    });

    $('body').on('click', '.edit-item-btn', function () {
        var id = $(this).data('id');
        $('#form-errors').addClass('d-none').empty();
        $.get(baseUrl + '/' + id + '/edit', function (data) {
            $('#modelHeading').text('Editar cotización');
            $('#id').val(data.id);
            $('#cash_id').val(data.cash_id);
            $('#client_id').val(data.client_id || '');
            $('#type').val(data.type);
            $('#amount').val(data.amount);
            $('#valid_until').val(data.valid_until ? String(data.valid_until).substring(0, 10) : '');
            $('#notes').val(data.notes || '');
            $('#status').val(data.status);
            refreshPreview();
        });
    });

    $('#Form').on('submit', function (e) {
        e.preventDefault();
        $('#saveBtn').prop('disabled', true).text('Guardando…');
        $('#form-errors').addClass('d-none').empty();

        $.ajax({
            data: $('#Form').serialize(),
            url: "{{ route('admin.quotation.store') }}",
            type: 'POST',
            dataType: 'json',
            success: function () {
                $('#Form').trigger('reset');
                $('#showModal').modal('hide');
                table.draw(false);
                $('#saveBtn').prop('disabled', false).text('Guardar');
            },
            error: function (xhr) {
                var msg = 'No se pudo guardar la cotización.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                $('#form-errors').removeClass('d-none').html(msg);
                $('#saveBtn').prop('disabled', false).text('Guardar');
            }
        });
    });

    $('body').on('click', '.remove-item-btn', function () {
        deleteId = $(this).data('id');
    });

    $('#delete-record').click(function () {
        if (!deleteId) return;
        $.ajax({
            type: 'DELETE',
            url: baseUrl + '/' + deleteId,
            success: function () {
                $('#deleteRecordModal').modal('hide');
                table.draw(false);
                deleteId = null;
            }
        });
    });

    $('body').on('click', '.convert-item-btn', function () {
        convertId = $(this).data('id');
        $('#convert-errors').addClass('d-none').empty();
    });

    $('#convert-record').click(function () {
        if (!convertId) return;
        var btn = $(this);
        btn.prop('disabled', true).text('Convirtiendo…');
        $.ajax({
            type: 'POST',
            url: baseUrl + '/' + convertId + '/convert',
            success: function () {
                $('#convertRecordModal').modal('hide');
                table.draw(false);
                convertId = null;
                btn.prop('disabled', false).text('Sí, convertir');
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error))
                    || 'No se pudo convertir la cotización.';
                $('#convert-errors').removeClass('d-none').text(msg);
                btn.prop('disabled', false).text('Sí, convertir');
            }
        });
    });
});
</script>
@endsection
