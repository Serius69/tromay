@extends('layout.masteradmin')
@section('body')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Compra de Moneda</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Inicio</a></li>
                            <li class="breadcrumb-item active">Compra de Moneda</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">


            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Form Grid</h4>
                    </div><!-- end card header -->

                    <div class="card-body">

                        <div class="live-preview">
                            <p class="text-muted">Ingresa los datos de forma correcta </p>
                            <form action="javascript:void(0);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">CI</label>
                                            <input type="text" class="form-control" placeholder="Enter your firstname" id="firstNameinput">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" placeholder="Enter your firstname" id="firstNameinput">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lastNameinput" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" placeholder="Enter your lastname" id="lastNameinput">
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">Monto</label>
                                            <input type="text" class="form-control" placeholder="Enter your firstname" id="firstNameinput">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div data-input-flag data-option-flag-img-name>
                                                <input type="text" class="form-control rounded-end flag-input" readonly value="United States" placeholder="Select country" data-bs-toggle="dropdown" aria-expanded="false" />
                                                <div class="dropdown-menu w-100">
                                                    <div class="p-2 px-3 pt-1 searchlist-input">
                                                        <input type="text" class="form-control form-control-sm border search-countryList" placeholder="Search country name or country code..." />
                                                    </div>
                                                    <ul class="list-unstyled dropdown-menu-list mb-0"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lastNameinput" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" placeholder="Enter your lastname" id="lastNameinput">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->


                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>


@endsection
