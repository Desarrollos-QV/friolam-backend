
<div class="tab-content" id="myTabContent1">
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		<div class="row g-3" style="padding-bottom: 1rem;">
			<div class="form-group col-md-6">
				<label for="inputEmail6">Codigo del cupón</label>
				<input type="text" name="code" class="form-control" value="{{$data->code}}" required >
			</div>

			<div class="form-group col-md-6">
				<label for="inputEmail6">Valor minimo del viaje <small>(opcional)</small></label>
				<input type="text" name="min_cart_value" class="form-control" value="{{$data->min_cart_value}}">
			</div>
		</div>

		<div class="row g-3" style="padding-bottom: 1rem;">
			<div class="form-group col-md-6">
				<label for="inputEmail6">Tipo de descuento</label>
				<select name="type" class="form-select">
					<option value="0" @if($data->type == 0) selected @endif>Porcentaje %</option>
					<option value="1" @if($data->type == 1) selected @endif>Valor Fijo</option>
				</select>
			</div>

			<div class="form-group col-md-6">
				<label for="inputEmail6">Valor del descuento</label>
				<input type="number" name="value" class="form-control" value="{{$data->value}}" required>
			</div>
		</div>

		<div class="row g-3" style="padding-bottom: 1rem;">
			<div class="form-group col-md-3">
				<label for="inputEmail6">Status</label>
				<select name="status" class="form-select">
					<option value="0" @if($data->status == 0) selected @endif>Activo</option>
					<option value="1" @if($data->status == 1) selected @endif>Inactivo</option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="inputEmail6">Imagen Descriptiva</label>
				<input type="file" name="img" class="form-control"  @if(!$data->id) required="required" @endif>
			</div>

			<div class="form-group col-md-6">
				<label for="inputEmail6">Descripción</label>
				<input type="text" name="description" class="form-control" value="{{$data->description}}" required>
			</div>
		</div>
	</div>
</div>
<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
