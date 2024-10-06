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
                        <h4 class="card-title mb-0 flex-grow-1">Datos Transaccion</h4>
                    </div><!-- end card header -->
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="card-body">
                        <div class="live-preview">
                            <p class="text-muted">Ingresa los datos de forma correcta </p>
                            <form action="javascript:void(0);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">CI</label>
                                            <input type="text" class="form-control" placeholder="Ingresa el carnet de identidad del cliente" id="firstNameinput">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">Nombre</label>
                                            <input type="text" class="form-control" placeholder="Ingresa el nombre del cliente" id="firstNameinput">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lastNameinput" class="form-label">Apellido</label>
                                            <input type="text" class="form-control" placeholder="Ingresa el apellido del cliente" id="lastNameinput">
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">Monto</label>
                                            <input type="number" class="form-control" placeholder="Ingresa el monto que se cambiara" id="firstNameinput">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">Moneda 1</label>
                                            <div data-input-flag data-option-flag-img-name>
                                                <select class="form-select mb-3" aria-label="Default select example">
                                                    <option selected>Moneda 1</option>
                                                    @foreach ($cash1 as $cash)
                                                    @if(($cash->status)==1)
                                                        {{--  <option value={{$cash->$id}}>{{$cash->$name}}</option>  --}}
                                                    @endif                                                        
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-md-6">
                                        <div class="mb-6">
                                            <label for="lastNameinput" class="form-label">Monto Final</label>
                                                <input type="text" class="form-control" id="disabledInput" value="Disabled input" disabled>
                                                <button type="calculate" class="btn btn-secondary" onclick="calculate()">Calcular</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="firstNameinput" class="form-label">Moneda 2</label>
                                            <div data-input-flag data-option-flag-img-name>
                                                <select class="form-select mb-3" aria-label="Default select example">
                                                    <option selected>Moneda 2</option>
                                                    @foreach ($cash1 as $cash)
                                                    @if(($cash->status)==1)
                                                        {{--  <option value={{$cash->$id}}>{{$cash->$name}}</option>  --}}
                                                    @endif
                                                        
                                                    @endforeach
                                                </select>
                                            </div>
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
