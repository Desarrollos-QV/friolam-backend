@extends('layouts.app')
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Solicitudes</div>
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

        @if(Auth::guard('admin')->user()->branch_id == 0)
        <div class="row">
            <div class="col-xl-11 mx-auto" style="text-align: right;">
                <a href="{{ Asset($link.'add') }}" >
                    <button type="button" class="btn btn-success px-3 radius-10">Agregar Servicio</button>
                </a>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-11 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table mb-0" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Servicio a realizar</th>
                                        <th>Cliente</th>
                                        <th>SubCliente</th>  
                                        <th># Factura</th>
                                        <th>Status</th>
                                        <th>Trabajador</th>    
                                        <th style="text-align:right;">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $row)
                                        <tr>
                                            <td>
                                                #{{ $row->id }}
                                            </td>
                                            <td>{{ $row->service_name }}</td>
                                            <td >
                                                {{$row->name_user}}
                                            </td>
                                            <td >
                                                {{$row->subcliente}}
                                            </td> 
                                            <td >
                                                #{{$row->factura}}
                                            </td> 
                                            <td>
                                                @if($row->status == 0) <!-- Servicio Nuevo -->
                                                <div class="badge rounded-pill text-info bg-light-success p-2 text-uppercase px-3">
                                                    <b>Nuevo</b>
                                                </div>
                                                @elseif($row->status == 1) <!-- Servicio Asignado -->
                                                <div class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3">
                                                    <b>Asignado</b>
                                                </div>
                                                @elseif($row->status == 2) <!-- Servicio Cancelado -->
                                                <div class="badge rounded-pill text-info bg-light-danger p-2 text-uppercase px-3">
                                                    <b>Cancelado</b>
                                                </div>
                                                @elseif($row->status == 3) <!-- Servicio Finalizado -->
                                                <div class="badge rounded-pill text-info bg-light-success p-2 text-uppercase px-3">
                                                    <b>Finalizado</b>
                                                </div>
                                                @endif
                                            </td> 
                                            <td>
                                                {{$service->viewDboy($row->id)}}
                                            </td>   
                                             <td style="text-align: right">
                                                <form action="{{url($form_url)}}" method="post" enctype="multipart/form-data" target="_blank">
                                                    @csrf
                                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acciones</button>
                                                    <input type="hidden" name="staff_id" value="{{$row->id}}">
                                                    <input type="hidden" name="type_report" value="excel">
                                                    <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
                                                        <li><a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">Editar</a></li>
                                                        @if($row->status == 0 || $row->status == 1)
                                                        <li><a href="{{ Asset($link.'status/'.$row->id) }}/3" class="dropdown-item">Finalizar</a></li>
                                                        <li><a href="{{ Asset($link.'status/'.$row->id) }}/2" class="dropdown-item">Cancelar</a></li>
                                                        @elseif($row->status == 5)
                                                            @if($row->commaned)
                                                                <li><a href="{{ base64_decode($row->commaned->signature) }}" target="_blank" class="dropdown-item">Ver Firma de entrega</a></li>
                                                            @else 
                                                            <li>
                                                               <a class="dropdown-item"> No hay firma</a>
                                                            </li>
                                                            @endif
                                                        @endif
                                                        <li><button type="button" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Repartidor" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="lni lni-trash"></i>&nbsp;&nbsp;&nbsp; Eliminar </button></li>
                                                    </ul>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

@endsection
