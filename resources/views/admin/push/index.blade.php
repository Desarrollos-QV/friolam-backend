@extends('layouts.app')

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Notificaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Generales</li>
                    </ol>
                </nav>
            </div>
           
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        <form action="{{url($form_url)}}" method="post" enctype="multipart/form-data" target="_blank">
                            @csrf
                            <div class="row g-3" style="padding-bottom: 1rem;">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail6">Titulo</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                            </div>

                            <div class="row g-3" style="padding-bottom: 1rem;">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail6">Selección de Ciudades</label>
                                    <select name="citys[]" class="multiple-select" data-placeholder="Choose anything" multiple="multiple">
                                        <option value="all">Todas</option>
                                        @foreach($citys as $p)
                                        <option value="{{ $p->id }}" @if(in_array($p->name,$array)) selected @endif>{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3" style="padding-bottom: 1rem;">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail6">Imagen (Tamaño recomendado 800px x 600px)</label>
                                    <input type="file" name="img" class="form-control">
                                </div>
                            </div>

                            <div class="row g-3" style="padding-bottom: 1rem;">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail6">Descripción (menos de 250 palabras )</label>
                                    <textarea name="desc" id="desc" class="form-control" maxlength="250"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-cta">Enviar Notificación</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
