@extends('layouts.app')

@section('style')
<style>    
    .results tr[visible='false'],
    .no-result{
    display:none;
    }

    .results tr[visible='true']{
    display:table-row;
    }

    .counter{
    padding:8px; 
    color:#ccc;
    }
</style>
@endsection

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

        <!-- BTNS -->
        <div class="row">
            <div class="col-xl-12 mx-auto" style="text-align: right;">
                <a href="{{ Asset('importSubUsers') }}" >
                    <button type="button" class="btn btn-info px-3 radius-10">Importar .xls</button>
                </a>&nbsp;
                <a href="{{ Asset($link.'add') }}" >
                    <button type="button" class="btn btn-success px-3 radius-10">Agregar Sub Cliente</button>
                </a>
            </div>
        </div>
        <!-- BTNS -->

        <!-- BUSCADOR -->
        <div class="row">
            <div class="col-xl-4">
                <div class="form-group pull-right">
                    <input type="text" class="search form-control" placeholder="Buscar SubClientes">
                </div>
            </div>
        </div>
        <!-- BUSCADOR -->

        
        <!-- TABLA -->
        <div class="row">
            <div class="col-md-12mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-hover table-bordered results" style="width:100%">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Razón Social</th>
                                        <th>Direción</th>
                                        <th>Numero</th>
                                        <th>Comuna</th>
                                        <th>Canal</th>
                                        <th>Status</th>
                                        <th style="text-align: right">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $row)
                                        <tr>
                                            <td>#{{ $row->id }}</td>
                                            <td>{{ $row->razon_social }}</td>
                                            <td>
                                                {{$row->direccion}}
                                            </td>
                                            <td>
                                               {{ $row->numero }}
                                            </td>
                                            <td>
                                                {{ $row->comuna }}
                                            </td>
                                            <td>
                                                {{ $row->canal }}
                                            </td>
                                            <td>
                                                @if($row->status == 0)
                                                <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-info" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Active</button>
                                                @else
                                                <button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger" onclick="confirmAlert('{{ Asset($link.'status/'.$row->id) }}')">Disabled</button>
                                                @endif
                                            </td>
                                            <td style="text-align: right">
                                                <button class="btn btn-primary dropdown-toggle" 
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Opciones
                                                </button>
                                                
                                                <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
                                                    <li>
                                                        <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                                                            Editar
                                                        </a>
                                                    </li>
                                                    <!-- Delete -->
                                                    <li>
                                                        <a href="javascript::void()" class="dropdown-item" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')">
                                                            Eliminar
                                                        </a>
                                                    </li>
                                                </ul>
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
        <!-- TABLA -->

        <!-- PAGINACION -->
        <div class="row">
            <div class="col-xl-12 mx-auto" style="text-align: right;">
                {!! $data->links() !!}
            </div>
        </div>
        <!-- PAGINACION -->
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $(".search").keyup(function () {
        var searchTerm = $(".search").val(); 
        var listItem = $('.results tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
            
        $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
                return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });
            
        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
            $(this).attr('visible','false');
        });

        $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
            $(this).attr('visible','true');
        });

        var jobCount = $('.results tbody tr[visible="true"]').length;
            $('.counter').text(jobCount + ' item');

        if(jobCount == '0') {$('.no-result').show();}
            else {$('.no-result').hide();}
    });
});
</script>
@endsection