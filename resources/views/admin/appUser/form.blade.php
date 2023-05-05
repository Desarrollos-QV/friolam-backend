


<div class="row g-3" style="padding-bottom: 1rem;">

	<div class="form-group col-md-6">
		<label for="name">Nombre</label>
		<input type="text" name="name" class="form-control" value="{{$data->name}}">
	</div>

	<div class="form-group col-md-6">
		<label for="lastname">Apellidos</label>
		<input type="text" name="lastname" class="form-control" value="{{$data->lastname}}">
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">

	<div class="form-group col-md-6">
		<label for="email">Correo Electronico</label>
		<input type="mail" name="email" class="form-control" value="{{$data->email}}">
	</div>

	<div class="form-group col-md-6">
		<label for="phone">Telefono</label>
		<input type="tel" name="phone" class="form-control" value="{{$data->phone}}">
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-6">
		<label for="rut">RUT</label>
		<input type="text" name="rut" class="form-control" value="{{$data->rut}}">
	</div>

	<div class="form-group col-md-3">
		<label for="date_of_hire">Fecha de contratación</label>
		<input type="date" name="date_of_hire" class="form-control" value="{{$data->date_of_hire}}">
	</div>

	<div class="form-group col-md-3">
		<label for="pic">Foto de perfil</label>
		<input type="file" name="pic" class="form-control"  @if(!$data->id) required="required" @endif>
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1rem;">
	<div class="form-group col-md-6">
		<label for="inputEmail6">Status</label>
		<select name="status" class="form-control">
			<option value="0" @if($data->status == 0) selected @endif>Active</option>
			<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
		</select>
	</div>

	<div class="form-group col-md-6">
		@if($data->id)
			<label for="pass_new">Cambiar Contraeña</label>
			<input type="password" id="pass_new" name="password" class="form-control">
		@else
			<label for="pass">Contraseña</label>
			<input type="password" id="pass" name="password" class="form-control" required="required">
		@endif
	</div>
</div>

	

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
