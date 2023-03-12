
<div class="row g-3" style="padding-bottom: 1rem;">

	<div class="form-group col-md-6">
		<label for="name">Nombre</label>
		<input type="text" name="name" class="form-control" value="{{$data->name}}">
	</div>
	<div class="form-group col-md-6">
		<label for="city_id">Ciudad</label>
		<select name="city_id" id="city_id" class="form-select" required="required">
		<option value="">Select City</option>
		@foreach($citys as $city)
		<option value="{{ $city->id }}" @if($data->city_id == $city->id) selected @endif>{{ $city->name }}</option>
		@endforeach
		</select>
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-6">
		<label for="phone">Telefono (This will be username)</label>
		<input type="text" name="phone" class="form-control" value="{{$data->phone}}" required>
	</div>

	<div class="form-group col-md-6">
		<label for="rfc">Cedula</label>
		<input type="text" id="rfc" name="rfc" value="{{$data->rfc}}" required class="form-control">
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-6">
		<label for="type_edriver">Tipo de conductor</label>
		<select name="type_edriver" id="type_edriver" class="form-select">
			@foreach ($vehicles as $vh)
				<option value="{{$vh->id}}" @if($data->type_driver == $vh->id) selected @endif>{{$vh->type_driver}}</option>
			@endforeach
		</select>
	</div>

	<div class="form-group col-md-6">
		<label for="status">Estado</label>
		<select name="status" id="status" class="form-control">
			<option value="0" @if($data->status == 0) selected @endif>Activo</option>
			<option value="1" @if($data->status == 1) selected @endif>Inactivo</option>
		</select>
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-12">
		@if($data->id)
			<label for="pass_new">Cambiar Contraeña</label>
			<input type="password" id="pass_new" name="password" class="form-control">
		@else
			<label for="pass">Contraseña</label>
			<input type="password" id="pass" name="password" class="form-control" required="required">
		@endif
	</div>
</div>
