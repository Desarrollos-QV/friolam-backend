@extends("layouts.app")

@section('title') Información de su cuenta @endsection

@section('icon') mdi-settings @endsection


@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Configuraciones</li>
                    </ol>
                </nav>
            </div>
           
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-9 col-md-12 col-sm-12 mx-auto">
                <form action="{{ $form_url }}" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="tab-content" id="myTabContent1">
                         <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                             <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    @if($data->logo)
                                        <img src="{{ Asset('upload/admin/'.$data->logo) }}" class="logo-icon-system">
                                    @endif

                                    <div class="row g-3" style="padding-bottom: 1rem;padding-top: 3rem;">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail6">Nombre del Sistema</label>
                                            <input type="text" value="{{ $data->name }}" class="form-control"
                                                    id="inputEmail6" name="name" required="required">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail4">Correo Electronico</label>
                                            <input type="email" class="form-control" id="inputEmail4" name="email"
                                                    value="{{ $data->email }}" required="required">
                                        </div>
                                    </div>

                                    <div class="row g-3" style="padding-bottom: 1rem;">
                                        <div class="form-group col-md-6">
                                            <label for="asd">Usuario</label>
                                            <input type="text" class="form-control" id="asd" name="username"
                                                    value="{{ $data->username }}" required="required">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="asd">Moneda <small>(e.g $, &pound;
                                                    &#8377;)</small></label>
                                            <input type="text" class="form-control" id="asd" name="currency"
                                                    value="{{ $data->currency }}" required="required">
                                        </div>
                                    </div>

                                    <div class="row g-3" style="padding-bottom: 1rem;">
                                        <div class="form-group col-md-6">
                                            <label for="asd">Logo</label>
                                            <input type="file" class="form-control" id="asd" name="logo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent1">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h4>Google ApiKey <br /><small style="font-size: 12px">(Introduce el ApiKey de tu cuenta en <a href="https://cloud.google.com/"
                                                target="_blank">https://cloud.google.com/</a> )</small></h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="ApiKey_google">ApiKey</label>
                                            <input type="text" class="form-control" id="ApiKey_google"
                                                    name="ApiKey_google" value="{{ $data->ApiKey_google }}">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent1">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h1 style="font-size: 20px">Establecer cargos de comisión por pago con tarjeta</h1>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="row g-3" style="padding-bottom: 1rem;">
                                        <div class="form-group col-md-6">
                                            <label for="inputEmail6">Terminal a domicilio</label>

                                            <select name="send_terminal" class="form-select">
                                                <option value="0" @if($data->send_terminal == 0) selected
                                                    @endif>No Brindar Servicio</option>
                                                <option value="1" @if($data->send_terminal == 1) selected
                                                    @endif>Brindar Servicio</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="comm_stripe">Valor de la comisión <small>(% que se
                                                    cobrara)</small> </label>
                                            <input type="text" name="comm_stripe"
                                                    value="{{$data->comm_stripe}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent1">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h4>Stripe Setting <br /><small style="font-size: 12px">(Deja vacío si quieres
                                            deshabilitar Stripe)</small></h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="asd">Stripe Publish Key</label>
                                            <input type="text" class="form-control" id="asd"
                                                    name="stripe_client_id"
                                                    value="{{ $data->stripe_client_id }}">
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="asd">Stripe API Key</label>
                                            <input type="text" class="form-control" id="asd"
                                                    name="stripe_api_id" value="{{ $data->stripe_api_id }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent1">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h4>PayPal Setting <br /><small style="font-size: 12px">(Deja vacío si quieres
                                            deshabilitar paypal)</small></h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="asd">PayPal Client ID</label>
                                            <input type="text" class="form-control" id="asd"
                                                    name="paypal_client_id"
                                                    value="{{ $data->paypal_client_id }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent1">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h4>Redes Sociales</h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">

                                    <div class="row g-3" style="padding-bottom: 1rem;">
                                        <div class="form-group col-md-6">
                                            <label for="asd">Facebook</label>
                                            <input type="text" class="form-control" id="asd" name="fb"
                                                    value="{{ $data->fb }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="asd">Instagram</label>
                                            <input type="text" class="form-control" id="asd" name="insta"
                                                    value="{{ $data->insta }}">
                                        </div>
                                    </div>

                                    <div class="row g-3" style="padding-bottom: 1rem;">
                                        <div class="form-group col-md-6">
                                            <label for="asd">Twitter</label>
                                            <input type="text" class="form-control" id="asd" name="twitter"
                                                    value="{{ $data->twitter }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="asd">Youtube</label>
                                            <input type="text" class="form-control" id="asd" name="youtube"
                                                    value="{{ $data->youtube }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="myTabContent1">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <h4>Contraseña de confirmación</h4>
                            <div class="card py-3 m-b-30">
                                <div class="card-body">

                                    <div class="row g-3" style="padding-bottom: 1rem;">
                                        <div class="form-group col-md-6">
                                            <label for="inputPassword4">Contraseña actual</label>
                                            <input type="password" class="form-control" id="inputPassword4"
                                                    name="password" required="required"
                                                    placeholder="Enter Your Current Password For Save Setting">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="inputPassword4">Nueva Contraseña <small
                                                        style="color:red">(Solo si deseas cambiarla)</small></label>
                                            <input type="password" class="form-control" id="inputPassword4"
                                                    name="new_password">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
