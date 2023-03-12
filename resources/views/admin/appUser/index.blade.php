@extends('layouts.app')
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Trabajadores Registrados</div>
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
                    <button type="button" class="btn btn-success px-3 radius-10">Agregar Usuario</button>
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
                                        <th>&nbsp;</th>
                                        <th>Usuario</th>
                                        <th>Email</th>
                                        <th>Telefono</th>
                                        <th>Fecha de registro</th> 
                                        <th>Estado</th>
                                        <th>Eliminar</th>
                                    </tr>

                                </thead>
                                <tbody>

                                    @foreach($data as $row)

                                    <tr>
                                        <td width="5%">
                                            @if($row->pic != '')
                                            <img src="{{ Asset('upload/workers/'.$row->pic) }}" style="width: 50px;height: 50px;border-radius: 2003px;">
                                            @else 
                                            <img src="{{ Asset('assets/images/icons/idea.png') }}" style="width: 50px;height: 50px;border-radius: 2003px;">
                                            @endif
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{{ $row->phone }}</td>
                                        <td>{{ date('d-M-Y',strtotime($row->created_at)) }}</td>
                                         
                                        <td>
                                            @if($row->status == 0)
                                            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success"
                                                    onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Activo</button>
                                            @else
                                            <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger"
                                                    onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Bloqueado</button>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acciones
                                            </button>
                                            <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
                                                <li><a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item"><i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Editar</a></li>
                                                <li>    
                                                    <button type="button" class="dropdown-item "
                                                        onclick="confirmAlert('{{ Asset($link.'trash/'.$row->id) }}')">
                                                        <i class="lni lni-trash">&nbsp;&nbsp;&nbsp; Eliminar </i>
                                                    </button>
                                                </li>

                                        </td>
                                    </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {!! $data->links() !!}
            </div>
        </div>
    </div>
</div>

@endsection
