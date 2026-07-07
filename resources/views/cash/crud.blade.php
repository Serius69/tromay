
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
                                <h4 class="mb-sm-0">Listjs</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                        <li class="breadcrumb-item active">Listjs</li>
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
                                    <h4 class="card-title mb-0">Aniadir, Editar o Eliminar</h4>
                                </div><!-- end card header -->

                                <div class="card-body">
                                    <div id="customerList">
                                        <div class="row g-4 mb-3">
                                            <div class="col-sm-auto">
                                                <div>
                                                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="createNewroom" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Add</button>
                                                    <button class="btn btn-soft-danger" onClick="if(confirm('¿Eliminar los registros seleccionados? Esta acción no se puede deshacer.'))deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-sm">
                                                <div class="d-flex justify-content-sm-end">
                                                    <div class="search-box ms-2">
                                                        <input type="text" class="form-control search" placeholder="Search...">
                                                        <i class="ri-search-line search-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive table-card mt-3 mb-1">
                                            <table class="table align-middle table-nowrap">
                                                <thead class="table-light">
                                                    <tr>
                                                        {{--  <th scope="col" style="width: 50px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                                            </div>
                                                        </th>  --}}
                                                        <th>Nro</th>
                                                        <th class="sort" data-sort="name">Moneda</th>
                                                        <th class="sort" data-sort="buy">Compra</th>
                                                        <th class="sort" data-sort="sell">Venta</th>
                                                        <th class="sort" data-sort="oficial">Oficial</th>
                                                        <th class="sort" data-sort="path">Imagen</th>
                                                        <th class="sort" data-sort="status">Estado</th>
                                                        <th>Accion</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="list form-check-all">
                                                    
                                                </tbody>
                                            </table>
                                            <div class="noresult" style="display: none">
                                                <div class="text-center" style="padding:40px 20px;color:#64748b;">
                                                    <div style="font-size:32px;margin-bottom:10px;">📋</div>
                                                    <h5 class="mt-2">No hay registros todavía</h5>
                                                    <p class="text-muted mb-0">Las cotizaciones que crees aparecerán aquí. Usa el botón <strong>Add</strong> para agregar la primera.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <div class="pagination-wrap hstack gap-2">
                                                <a class="page-item pagination-prev disabled" href="#">
                                                    Anterior
                                                </a>
                                                <ul class="pagination listjs-pagination mb-0"></ul>
                                                <a class="page-item pagination-next" href="#">
                                                    Siguiente
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end card -->
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->

                    {{-- POPUP FORM --}}
                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-light p-3">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                </div>
                                <form id="cashForm" name="cashForm" class="form-horizontal">
                                    <div class="modal-body">
                                        <div class="mb-3" id="modal-id" style="display: none;">
                                            <label for="id-field" class="form-label">ID</label>
                                            <input type="text" id="id" name="id" class="form-control" placeholder="ID" readonly />
                                        </div>

                                        <div class="mb-3">
                                            <label for="customername-field" class="form-label">Nombre de la divisa <span style="color:#ef4444">*</span></label>
                                            <input type="text" id="id_name" name="name" class="form-control" placeholder="Ej: Dólar Americano" required />
                                            <small class="form-text" style="color:#64748b;font-size:12px;margin-top:4px;">Nombre completo de la moneda que se cotizará.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email-field" class="form-label">Compra <span style="color:#ef4444">*</span></label>
                                            <input type="number" id="id_buy" name="buy" class="form-control" step="0.0001" min="0" placeholder="Ej: 6.9600" required />
                                            <small class="form-text" style="color:#64748b;font-size:12px;margin-top:4px;">💡 Precio al que Kapitalya <strong>compra</strong> la moneda extranjera. Usa hasta 4 decimales (ej: 6.9700).</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone-field" class="form-label">Venta <span style="color:#ef4444">*</span></label>
                                            <input type="number" id="id_sell" name="sell" class="form-control" step="0.0001" min="0" placeholder="Ej: 7.0500" required />
                                            <small class="form-text" style="color:#64748b;font-size:12px;margin-top:4px;">💡 Precio al que Kapitalya <strong>vende</strong> la moneda extranjera. Normalmente mayor que Compra.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="date-field" class="form-label">Oficial <span style="color:#ef4444">*</span></label>
                                            <input type="number" id="id_oficial" name="oficial" class="form-control" step="0.0001" min="0" placeholder="Ej: 6.9600" required />
                                            <small class="form-text" style="color:#64748b;font-size:12px;margin-top:4px;">Tipo de cambio publicado por el Banco Central de Bolivia (BCB). Consulta en <a href="https://www.bcb.gob.bo" target="_blank" rel="noopener" style="color:#2563eb;">bcb.gob.bo</a>.</small>
                                        </div>

                                        <div>
                                            <label for="status-field" class="form-label">Estado</label>
                                            <select class="form-control" data-trigger name="status-field" id="status-field">
                                                <option value="">Selecciona el estado</option>
                                                <option value="Active">Activo — visible en el sitio público</option>
                                                <option value="Block">Bloqueado — oculto para los visitantes</option>
                                            </select>
                                            <small class="form-text" style="color:#64748b;font-size:12px;margin-top:4px;">Solo las cotizaciones "Activas" aparecen en la página principal.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-success" id="add-btn">Guardar cotización</button>
                                            <button type="button" class="btn btn-success" id="edit-btn">Actualizar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mt-2 text-center">
                                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                            <h4>¿Estás seguro?</h4>
                                            <p class="text-muted mx-4 mb-0">¿Deseas eliminar esta cotización? Esta acción no se puede deshacer.</p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn w-sm btn-danger" id="delete-record">Sí, eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end modal -->
                </div>
            </div>
        </div>
 <!-- container-fluid -->
@endsection


@section('script')
<script type="text/javascript">
    $(function () {

      /*------------------------------------------
       --------------------------------------------
       Pass Header Token
       --------------------------------------------
       --------------------------------------------*/
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      });

      /*------------------------------------------
      --------------------------------------------
      Render DataTable
      --------------------------------------------
      --------------------------------------------*/
      var table = $('.table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('cashes.index') }}",
          columns: [
              {data: 'DT_RowIndex', name: 'DT_RowIndex'},
              {data: 'name', name: 'name'},
              {data: 'buy', name: 'buy'},
              {data: 'sell', name: 'sell'},
              {data: 'oficial', name: 'oficial'},
              {
                data: 'path', name: 'path' , 
                render: function (data, type, full, meta) {
                    return "<img src=\"../assets/img/cash/" + data + "\" height=\"50\"/>";
                }
              },
              {
                data: 'status', name: 'status'
                },
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });

      /*------------------------------------------
      --------------------------------------------
      Click to Button
      --------------------------------------------
      --------------------------------------------*/
      $('#createNewcash').click(function () {
          $('#saveBtn').val("create-cash");
          $('#cash_id').val('');
          $('#cashForm').trigger("reset");
          $('#modelHeading').html("Crear nueva Cotizacion");
          $('#ajaxModel').modal('show');
      });

      /*------------------------------------------
      --------------------------------------------
      Click to Edit Button
      --------------------------------------------
      --------------------------------------------*/
      $('body').on('click', '.editcash', function () {
        var cash_id = $(this).data('id');
        $.get("{{ route('cashes.index') }}" +'/' + cash_id +'/edit', function (data) {
            $('#modelHeading').html("Editar Cotizacion");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal('show');
            $('#cash_id').val(data.id);
            $('#name').val(data.name);
            $('#buy').val(data.buy);
        })
      });

      /*------------------------------------------
      --------------------------------------------
      Create cash Code
      --------------------------------------------
      --------------------------------------------*/
      $('#saveBtn').click(function (e) {
          e.preventDefault();
          $(this).html('Guardando..');

          $.ajax({
            data: $('#cashForm').serialize(),
            url: "{{ route('cashes.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {

                $('#cashForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                table.draw();

            },
            error: function (data) {
                console.log('Error:', data);
                $('#saveBtn').html('Save Changes');
            }
        });
      });

      /*------------------------------------------
      --------------------------------------------
      Delete cash Code
      --------------------------------------------
      --------------------------------------------*/
      $('body').on('click', '.deletecash', function () {

          var cash_id = $(this).data("id");
          if (!confirm("¿Estás seguro de eliminar esta cotización? Esta acción no se puede deshacer.")) return;

          $.ajax({
              type: "DELETE",
              url: "{{ route('cashes.store') }}"+'/'+cash_id,
              success: function (data) {
                  table.draw();
              },
              error: function (data) {
                  console.log('Error:', data);
              }
          });
      });

    });
  </script>
  @endsection
