@extends('layouts.app')

@section('content')

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Clientes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Imagen QR</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="card-body">
                    <div style="background:#fff;" class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                        <div class="col p-4 d-flex flex-column position-static">
                            <h3 class="mb-0">{{$data->name}}</h3>
                            <div class="mb-1 text-muted">Tel: {{$data->phone}}</div>
                            <p class="card-text mb-auto">
                                Email<br />
                                {{$data->email}}
                                <br />
                                Telefono: {{$data->phone}}
                            </p>
                            
                        </div>

                        <div class="col p-4 text-right">
                            <a download="qr_code_{{$data->name}}" href="data:image/png;base64,{{ $data->qr_code }}" target="_blank">
                                <img src="data:image/png;base64,{{ $data->qr_code }}">
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

