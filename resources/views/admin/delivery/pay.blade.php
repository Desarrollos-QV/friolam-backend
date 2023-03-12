@extends('layouts.app')

@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pagos.</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Agregar</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row ">
            <div class="col-lg-10 mx-auto  mt-2">
                <div class="card py-3 m-b-30">
                    <div class="card-body">
                        {!! Form::model($data, ['url' => [$form_url],'files' => true,'method' => 'PATCH'],['class' => 'col s12']) !!}
                        @csrf
                        <div class="form-row">
                            <input type="text" name="deliveryVia" value="admin" hidden>
                            <div class="form-group col-md-6">
                                <label for="inputEmail6">Monto adeudao</label>
                                <input type="text" disabled value="${{number_format($data->amount_acum,2)}}"
                                        class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pay_staff">Introduzca Pago</label>
                                <input type="number" id="pay_staff" name="pay_staff"
                                        placeholder="Ingresa el monto del pago" step=".01" class="form-control">
                            </div>
                        </div>
                        <br />
                        <button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
