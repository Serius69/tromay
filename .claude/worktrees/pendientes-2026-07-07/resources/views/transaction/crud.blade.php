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
                        <h4 class="mb-sm-0">Transacciones</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ url('admin') }}">Admin</a></li>
                                <li class="breadcrumb-item active">Transacciones</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">

                        {{-- Filter bar --}}
                        <div class="card-header border-bottom-0 pb-0">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-2">
                                    <label class="form-label fw-medium mb-1" for="f-date-from">Desde</label>
                                    <input type="date" id="f-date-from" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-medium mb-1" for="f-date-to">Hasta</label>
                                    <input type="date" id="f-date-to" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-medium mb-1" for="f-type">Tipo</label>
                                    <select id="f-type" class="form-select form-select-sm">
                                        <option value="">Todos</option>
                                        {{-- Perspectiva de la casa: 2 = la casa compra divisa, 1 = la casa vende --}}
                                        <option value="2">Compra</option>
                                        <option value="1">Venta</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-medium mb-1" for="f-cash">Divisa</label>
                                    <select id="f-cash" class="form-select form-select-sm">
                                        <option value="">Todas</option>
                                        @foreach($cashes as $cash)
                                        <option value="{{ $cash->id }}">{{ strtoupper($cash->getRawOriginal('name')) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button id="btn-filter" class="btn btn-primary btn-sm w-100">
                                        <i class="ri-filter-3-line me-1"></i>Filtrar
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button id="btn-clear" class="btn btn-light btn-sm w-100">
                                        <i class="ri-refresh-line me-1"></i>Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tx-table"
                                    class="table table-bordered dt-responsive nowrap table-striped align-middle"
                                    style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Fecha</th>
                                            <th>CI Cliente</th>
                                            <th>Nombre</th>
                                            <th>Divisa 1</th>
                                            <th>Divisa 2</th>
                                            <th>Monto 1</th>
                                            <th>Monto 2</th>
                                            <th>Tipo</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr class="table-secondary fw-semibold">
                                            <td colspan="6" class="text-end text-muted" style="font-size:12px;">
                                                Totales (página actual):
                                            </td>
                                            <td id="tfoot-amount1">—</td>
                                            <td id="tfoot-amount2">—</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Delete confirmation modal --}}
<div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center pb-2">
                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                    colors="primary:#f7b84b,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                <h4 class="mt-3">¿Eliminar transacción?</h4>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
                <div class="d-flex gap-2 justify-content-center mt-3 mb-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger px-4" id="confirm-delete">Sí, eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
$(function () {

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ----------------------------------------------------------------
    // DataTable
    // ----------------------------------------------------------------
    var table = $('#tx-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json',
            processing: '<span class="spinner-border spinner-border-sm me-2"></span>Cargando...'
        },
        ajax: {
            url: '{{ route("admin.transaction.index") }}',
            data: function (d) {
                d.date_from = $('#f-date-from').val();
                d.date_to   = $('#f-date-to').val();
                d.type      = $('#f-type').val();
                d.cash_id   = $('#f-cash').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false, width: '40px' },
            { data: 'date_fmt',     name: 'date' },
            { data: 'client_ci',    name: 'client_ci',    searchable: false },
            { data: 'client_name',  name: 'client_name',  searchable: false },
            { data: 'cash1_name',   name: 'cash1_name',   searchable: false },
            { data: 'cash2_name',   name: 'cash2_name',   searchable: false },
            { data: 'amount1_fmt',  name: 'amount1',      searchable: false },
            { data: 'amount2_fmt',  name: 'amount2',      searchable: false },
            { data: 'type_badge',   name: 'type',         searchable: false, orderable: false },
            { data: 'action',       name: 'action',       orderable: false,  searchable: false, width: '60px' },
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        dom: '<"row align-items-center mb-2"<"col-sm-6"l><"col-sm-4 text-center"B><"col-sm-2"f>>rtip',
        buttons: [
            {
                extend:    'excel',
                text:      '<i class="ri-file-excel-line me-1"></i>Excel',
                className: 'btn btn-success btn-sm',
                title:     'Transacciones',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] }
            }
        ],
        footerCallback: function (row, data) {
            var parseNum = function (s) {
                return parseFloat((s + '').replace(/,/g, '')) || 0;
            };
            var sum1 = 0, sum2 = 0;
            data.forEach(function (r) {
                sum1 += parseNum(r.amount1_fmt);
                sum2 += parseNum(r.amount2_fmt);
            });
            var fmt = function (n) {
                return n.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            };
            $('#tfoot-amount1').text(fmt(sum1));
            $('#tfoot-amount2').text(fmt(sum2));
        },
    });

    // Filter button
    $('#btn-filter').on('click', function () { table.draw(); });
    $('#btn-clear').on('click', function () {
        $('#f-date-from, #f-date-to').val('');
        $('#f-type, #f-cash').val('');
        table.draw();
    });

    // ----------------------------------------------------------------
    // Delete
    // ----------------------------------------------------------------
    var deleteId = null;

    $('body').on('click', '.remove-item-btn', function (e) {
        e.preventDefault();
        deleteId = $(this).data('id');
    });

    $('#confirm-delete').on('click', function () {
        if (!deleteId) return;
        $.ajax({
            url:  '{{ url("admin/transaction") }}/' + deleteId,
            type: 'DELETE',
            success: function () {
                $('#deleteRecordModal').modal('hide');
                table.draw();
                deleteId = null;
            },
            error: function () {
                alert('Error al eliminar. Intenta de nuevo.');
            }
        });
    });

});
</script>
@endsection
