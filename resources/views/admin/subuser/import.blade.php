@extends('layouts.app')

@section('wrapper')

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Sub Clientes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Listado</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->


        <div class="row ">
            <div class="col-lg-11 m-auto">
                <div class="card ">
                    <div class="card-body">
                        {!! Form::open(['url' => [Asset('importSubUsers')],'files' => true],['class' => 'col s12']) !!} 
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="import_lbl">
                                        Seleccion el Archivo<br />
                                        <small>(Recuerde ingresar los mismos campos en orden correspondiente)</small>
                                    </label>
                                    <input type="file" id="import_lbl" class="form-control" name="file" required="required">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-cta">Subir</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection