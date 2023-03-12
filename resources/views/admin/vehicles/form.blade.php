<h1 style="font-size: 20px">Información del conductor</h1>
<div class="card py-3 m-b-30">
    <div class="card-body">
        <div class="row g-3" style="padding-bottom: 1rem;">
			<div class="form-group col-md-6">
                <label for="number_plate">Placas</label>
                <input type="text" name="number_plate" id="number_plate" value="{{$data->number_plate}}" class="form-control">
            </div> 

			<div class="form-group col-md-6">
				<label for="type_driver">Tipo de vehiculo</label>
				<input type="text" name="type_driver" id="type_driver" value="{{$data->type_driver}}" class="form-control">
            </div> 
        </div>

        <div class="row g-3" style="padding-bottom: 1rem;">
            <div class="form-group col-md-6">
                <label for="capacity">Capacidad</label>
                <input type="number" step=".5" name="capacity" id="capacity" value="{{$data->capacity}}" class="form-control">
            </div>

			<div class="form-group col-md-6">
                <label for="brand">Marca</label>
                <input type="text" name="brand" id="brand" value="{{$data->brand}}" class="form-control">
            </div>
        </div>

        <div class="row g-3" style="padding-bottom: 1rem;">
			<div class="form-group col-md-4">
                <label for="model">Modelo</label>
                <input type="text" name="model" id="model" value="{{$data->model}}" class="form-control">
            </div>

            <div class="form-group col-md-4">
                <label for="color">Color</label>
                <input type="text" name="color" id="color" value="{{$data->color}}" class="form-control">
            </div>
            <div class="form-group col-md-4">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="0" @if($data->status == 0) selected @endif>Activo</option>
                    <option value="1" @if($data->status == 1) selected @endif>Inactivo</option>
                </select>
            </div>  
        </div> 
    </div>
</div>
 