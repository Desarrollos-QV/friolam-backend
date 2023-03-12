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
                        <li class="breadcrumb-item active" aria-current="page">Listado</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-9 mx-auto" style="text-align: right;">
                <a href="{{ Asset($link.'add') }}" >
                    <button type="button" class="btn btn-success px-3 radius-10">Agregar Conductor</button>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table mb-0" style="width:100%">
                                <thead class="table-dark">
                                <tr>
                                    <th>Conductor</th>
                                    <th>Nombre</th>
                                    <th>Telefono</th>
                                    <th>Contraseña</th>
                                    <th>Ciudad</th>
                                    <th>Saldo</th>
                                    <th>Status</th>
                                    <th style="text-align:right;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td width="10%">
                                            @if($row->type_edriver == 0)
                                                <img src="{{ Asset('upload/driver/trip2.png') }}" style="max-width:80%;">
                                            @else
                                                <img src="{{ Asset('upload/driver/trip.png') }}" style="max-width:80%;">
                                            @endif
                                        </td>
                                        <td width="17%">{{ $row->name }}</td>
                                        <td width="10%">{{ $row->phone }}</td>
                                        <td width="10%">{{ $row->shw_password }}</td>
                                        <td width="20%">
                                            {{$row->city}}
                                        </td>
                                        <td width="7%" align="center">
                                            @if($row->amount_acum > 0)
                                                ${{$row->amount_acum}}
                                            @else
                                                $0
                                            @endif
                                        </td>
                                        <td width="7%">
                                            @if($row->status == 0)
                                                <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button>
                                            @else
                                                <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button>
                                            @endif
                                        </td>
                                        <td width="10%" style="text-align: right">
                                            <form action="{{url($form_url)}}" method="post" enctype="multipart/form-data" target="_blank">
                                                @csrf
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acciones
                                                </button>
                                                <input type="hidden" name="staff_id" value="{{$row->id}}">
                                                <input type="hidden" name="type_report" value="excel">
                                                <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
                                                    <li><button type="submit" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="Descargar Reporte">
                                                        <i class="lni lni-cloud-download"></i>&nbsp;&nbsp;&nbsp; Descargar Reporte
                                                    </button></li>
                                                    <li><a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item"><i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Editar Repartidor</a></li></li>
                                                    
                                                    <li><a href="{{ Asset('upload/driver/'.$row->carnet) }}" target="_blank" class="dropdown-item"><i class="fadeIn animated bx bx-id-card"></i> &nbsp;&nbsp;&nbsp; Ver Carnet</a></li></li>
                                                    <li><a href="{{ Asset('upload/driver/'.$row->licence) }}" target="_blank" class="dropdown-item"><i class="fadeIn animated bx bx-id-card"></i> &nbsp;&nbsp;&nbsp; Ver Licencia</a></li></li>
                                                    

                                                    <li><a href="{{ Asset($link.$row->id.'/pay') }}" class="dropdown-item"><i class="fadeIn animated bx bx-dollar-circle"></i>&nbsp;&nbsp;&nbsp; Agregar Pago</a></li>
                                                    <li><a href="{{ Asset($link.$row->id.'/rate') }}" class="dropdown-item"><i class="fadeIn animated bx bx-chart"></i> &nbsp;&nbsp;&nbsp; Vista de Comentarios</a></li>
                                                    <li><button type="button" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Repartidor" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')"><i class="lni lni-trash"></i>&nbsp;&nbsp;&nbsp; Eliminar </button></li>

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

@endsection

