
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
                                <h4 class="mb-sm-0">ADMIN NOTICIAS</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">INICIO ADMIN</a></li>
                                        <li class="breadcrumb-item active">ADMIN NOTICIAS</li>
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
                                    <h4 class="card-title mb-0">Aniadir Modificar, Eliminar</h4>
                                </div><!-- end card header -->

                                <div class="card-body">
                                    <div id="customerList">
                                        <div class="row g-4 mb-3">
                                            <div class="col-sm-auto">
                                                <div>
                                                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="createNewroom" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Add</button>
                                                    <button class="btn btn-soft-danger" onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
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
                                            <table id="DataTables_Table_CRUD" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 10px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input fs-15" type="checkbox" id="checkAll" value="option">
                                                            </div>
                                                        </th>
                                                        <th class="sort" data-sort="description">Noticia</th>
                                                        <th class="sort" data-sort="description">Autor</th>
                                                        <th class="sort" data-sort="description">Descripcion</th>
                                                        <th class="sort" data-sort="date_publication">Fecha Publicacion</th>
                                                        <th class="sort" data-sort="url">Fecha Publicacion</th>
                                                        <th class="sort" data-sort="photo_id">Imagen</th>
                                                        <th class="sort" data-sort="type_id">Estado</th>
                                                        <th class="sort" data-sort="status">URL externa</th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                    </tbody>
                                            </table>
                                            <div class="noresult" style="display: none">
                                                <div class="text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                                    </lord-icon>
                                                    <h5 class="mt-2">Perdon no encontramos resultados</h5>
                                                    <p class="text-muted mb-0">
                                                        Buscamos entre los registros y no se encontro un resultado
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <div class="pagination-wrap hstack gap-2">
                                                <a class="page-item pagination-prev disabled" href="#">
                                                    Previous
                                                </a>
                                                <ul class="pagination listjs-pagination mb-0"></ul>
                                                <a class="page-item pagination-next" href="#">
                                                    Next
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

                    {{-- POP UP FORM --}}
                    <div class="modal fade" id="showModal"
                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modelHeading"></h5>
                                </div>

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                <strong>Whoops!</strong> Hay un error.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                </div>
                                @endif

                                <form id="Form" name="Form">
                                    <div class="modal-body">

                                        <div class="mb-3" id="modal-id" style="display: none;">
                                            <label for="id-field" class="form-label">ID</label>
                                            <input type="text" id="id" name="id" class="form-control" placeholder="ID" readonly />
                                        </div>

                                        <div class="mb-3">
                                            <label for="name-field" class="form-label">Noticia</label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" required />
                                        </div>

                                        <div class="mb-3">
                                            <label for="author-field" class="form-label">Autor</label>
                                            <input type="author" id="author" name="author" class="form-control" placeholder="Enter author" required />
                                        </div>

                                        <div class="mb-3">
                                            <label for="description-field" class="form-label">Descripcion</label>
                                            <input type="text" id="description" name="description" class="form-control" placeholder="Enter description no." required />
                                        </div>

                                        <div class="mb-3">
                                            <label for="date-field" class="form-label">Fecha Publicacion</label>
                                            <input type="date" id="date_publication" name="date_publication" class="form-control" required />
                                        </div>

                                        <div class="mb-3">
                                            <label for="url-field" class="form-label">URL externa</label>
                                            <input type="text" id="url" name="url" class="form-control" placeholder="Ingresa la URL" required />
                                        </div>
                                        <div>
                                            <label for="url-field" class="form-label">Imagen</label>
                                                <div class="dropzone">
                                                    <div class="fallback">
                                                        <input name="path" type="file" id="path" multiple="multiple">
                                                    </div>
                                                    <div class="dz-message needsclick">
                                                        <div class="mb-3">
                                                            <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                                        </div>

                                                        <h4>Drop files here or click to upload.</h4>
                                                    </div>
                                                </div>

                                                <ul class="list-unstyled mb-0" id="dropzone-preview">
                                                    <li class="mt-2" id="dropzone-preview-list">
                                                        <!-- This is used as the file preview template -->
                                                        <div class="border rounded">
                                                            <div class="d-flex p-2">
                                                                <div class="flex-shrink-0 me-3">
                                                                    <div class="avatar-sm bg-light rounded">
                                                                        <img data-dz-thumbnail class="img-fluid rounded d-block" src="#" alt="Dropzone-Image" />
                                                                    </div>
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <div class="pt-1">
                                                                        <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                                                        <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                                                    </div>
                                                                </div>
                                                                <div class="flex-shrink-0 ms-3">
                                                                    <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <!-- end dropzon-preview -->
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-success" id="saveBtn">Guardar Cambios</button>
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
                                            <h4>Are you Sure ?</h4>
                                            <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record ?</p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn w-sm btn-danger " id="delete-record">Yes, Delete It!</button>
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
        ajax: "{{ route('latests.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},
            {data: 'author', name: 'author'},
            {data: 'description', name: 'description'},
            {data: 'date_publication', name: 'date_publication'},
            {data: 'url', name: 'url'},
            {
                data: 'path', name: 'path' , 
                render: function (data, type, full, meta) {
                    return "<img src=\"../assets/img/latest/" + data + "\" height=\"50\"/>";
                }
            },
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });


    /*------------------------------------------
    --------------------------------------------
    Click to Button
    --------------------------------------------
    --------------------------------------------*/
    $('#createNewroom').click(function () {
        $('#saveBtn').val("create-latest");
        $('#latest_id').val('');
        $('#Form').trigger("reset");
        $('#modelHeading').html("Crear nueva Noticia");
        $('#showModal').modal('show');
    });

    /*------------------------------------------
    --------------------------------------------
    Click to Edit Button
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.edit', function () {
      var latest_id = $(this).data('id');
      $.get("{{ route('latests.index') }}" +'/' + latest_id +'/edit', function (data) {
        //   $('#exampleModalLabel').html("Editar Noticia");
          $('#saveBtn').val("edit-user");
          $('#showModal').modal('show');
          $('#latest_id').val(data.id);
          $('#name').val(data.name);
          $('#author').val(data.author);
          $('#description').val(data.description);
          $('#data_publication').val(data.data_publication);
          $('#url').val(data.url);
          $('#photo_id').val(data.photo_id);
        //   $('#status').val(data.status);
      })
    });

    /*------------------------------------------
    --------------------------------------------
    Create latest Code
    --------------------------------------------
    --------------------------------------------*/
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Guardando..');

        $.ajax({
          data: $('#Form').serialize(),
          url: "{{ route('latests.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
              $('#Form').trigger("reset");
              $('#showModal').modal('hide');
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
    Delete latest Code
    --------------------------------------------
    --------------------------------------------*/
    $('body').on('click', '.deletelatest', function () {

        var latest_id = $(this).data("id");
        confirm("Are You sure want to delete !");

        $.ajax({
            type: "DELETE",
            url: "{{ route('latests.store') }}"+'/'+latest_id,
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
