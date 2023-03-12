<h1 style="font-size: 20px">Cargos de comisión para el conductor</h1>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row g-3" style="padding-bottom: 1rem;">
            <div class="form-group col-md-6">
                <label for="c_type_staff">Tipo de comisión</label>
                <select name="c_type_staff" class="form-control">
                    <option value="1" @if($data->c_type_staff == 1) selected @endif>valor en %</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="c_value_staff">Valor de la comisión</label>
                <input type="text" name="c_value_staff" id="c_value_staff" value="{{$data->c_value_staff}}" class="form-control">
            </div>
        </div>
    </div>
</div> 

<h1 style="font-size: 20px">Información del conductor</h1>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row g-3" style="padding-bottom: 1rem;">
            <div class="form-group col-md-6">
                <label for="licence">Licencia de conducir</label>
                <input type="file" name="licence" id="licence" class="form-control"  @if(!$data->id) required="required" @endif>
            </div>

            <div class="form-group col-md-6">
                <label for="carnet">Carnet / INE</label>
                <input type="file" name="carnet" id="carnet" class="form-control"  @if(!$data->id) required="required" @endif>
            </div>
        </div>
    </div>
</div>
 
