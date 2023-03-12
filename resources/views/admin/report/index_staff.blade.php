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
                        <li class="breadcrumb-item active" aria-current="page">Reportes</li>
                    </ol>
                </nav>
            </div>
           
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{url($form_url)}}" method="post" enctype="multipart/form-data" target="_blank">
                            @csrf
                            <div class="row g-3" style="padding-bottom: 1rem;">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Seleccione el Socio Repartidor</label>
                                    <select name="staff_id" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach($data as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Tipo de reporte</label>
                                    <select name="type_report" class="form-select">
                                        <option value="excel">Excel</option>
                                        <option value="csv">CSV</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-cta">Imprimir Reporte</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
