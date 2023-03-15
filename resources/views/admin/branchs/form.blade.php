
<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">Información</h1>
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

		<div class="card py-3 m-b-30">
			<div class="card-body">

				<div class="row g-3" style="padding-bottom: 1rem;">
					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="name_branch">Nombre</label>
						<input id="name_branch" class="controls form-control" name="name" value="{{ $data->name }}" type="text">
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="name_contact">Persona de contacto</label>
						<input id="name_contact" class="controls form-control" name="name_contact" value="{{ $data->name_contact }}" type="text">
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="email_contact">Correo de contacto</label>
						<input id="email_contact" class="controls form-control" name="email_contact" value="{{ $data->email_contact }}" type="text">
					</div> 

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="status_branch">Status</label>
						<select name="status" id="status_branch" class="form-select">
							<option value="0" @if($data->status == 0) selected @endif>Active</option>
							<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
						</select>
					</div>

					<div class="form-group col-md-12">
						<label for="observations">Observaciones</label> 
						<textarea name="observations" id="observations" cols="10" rows="5" class="controls form-control">{{ $data->observations }}</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="tab-content" id="myTabContent2">
	<h1 style="font-size: 20px">Ubicación</h1>
	@include('admin.branchs.google')
</div>

<div class="tab-content" id="myTabContent2">
	<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
</div>


