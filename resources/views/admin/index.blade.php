<!doctype html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <title>Panel de administración</title>
</head>
<body class="bg-lock-screen">

<!-- wrapper -->
<div class="wrapper">
    <div class="authentication-reset-password d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-lg-5 border-end">
                            <div class="card-body">
                                <div class="p-5">
                                    <form  action="{{ $form_url }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="text-start">
                                            <img src="{{ asset('assets/images/logo-img.png') }}" width="180" alt="">
                                        </div>
                                        <h4 class="mt-5 font-weight-bold">Bienvenido(a) a tu panel</h4>
                                        <p class="text-muted">Ingresa a tu panel de administración con las claves de acceso proporcionadas.</p>
                                        <div class="mb-3 mt-5">
                                            <label for="username" class="form-label">Nombre de usuario</label>
                                            <input type="text" class="form-control" id="username" placeholder="Usuario" name="username">
                                        </div>
                                        <div class="mb-3">
                                            <label for="inputChoosePassword" class="form-label">Contraseña</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Contraseña"> 
                                                <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Ingresar</button> 
                                            <!-- <a href="{{ url('authentication-signin') }}" class="btn btn-light"><i class='bx bx-arrow-back mr-1'></i>Back to Login</a> -->
                                        </div>
                                    </form>
                                </div>

                                <div class="form-body" style="margin-top:25px;">
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="font-35 text-white">
                                                    <i class='bx bxs-message-square-x'></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0 text-white">ERROR</h6>
                                                    <div class="text-white">{{ Session::get('error') }}</div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if(Session::has('message'))
                                        <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="font-35 text-success">
                                                    <i class='bx bxs-check-circle'></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-0 text-success">SUCCESS</h6>
                                                    <div>{{ Session::get('message') }}</div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <img src="{{ asset('assets/images/login-images/forgot-password-frent-img.jpg') }}" class="card-img login-img h-100" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end wrapper -->
	<!--plugins-->
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
            
            $('.btn-close').on('click', function(event) {
                $('.alert').remove();
            });
        });
    </script>
</body>

</html>
