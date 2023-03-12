@extends('layouts.app')

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Conductores</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Agregar Elemento</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row ">
            <div class="col-lg-9 mx-auto">
                {!! Form::model($data, ['url' => [$form_url],'files' => true],['class' => 'col s12']) !!}
                    @csrf
                    <div class="card py-3 m-b-30">
                        <div class="card-body">
                            @include('admin.delivery.form')
                        </div>
                    </div>
                    @include('admin.delivery.comi_staff')
                    <button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
