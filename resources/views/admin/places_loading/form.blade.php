
<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">Información</h1>
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

		<div class="card py-3 m-b-30">
			<div class="card-body">

				<div class="row g-3" style="padding-bottom: 1rem;">
					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="type_place">Tipo de lugar</label>
						<select name="type_place" id="type_place" class="form-control">
							<option value="0" @if($data->type_place == 0) selected @endif>Carga</option>
							<option value="1" @if($data->type_place == 1) selected @endif>Descargue</option>
						</select>
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="name">Nombre <small>(identificación del tipo de descargue)</small></label>
						<input id="name" class="controls form-control" name="name" value="{{ $data->name }}" type="text">
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="download_cost">Costo de descargue <small>(Solo si el tipo es de descargue)</small></label>
						<input id="download_cost" class="controls form-control" name="download_cost" value="{{ $data->download_cost }}" type="text">
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="phone">Telefono</label>
						<input id="phone" class="controls form-control" name="phone" value="{{ $data->phone }}" type="tel">
					</div> 

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="person_contact">Persona de contacto</label>
						<input id="person_contact" class="controls form-control" name="person_contact" value="{{ $data->person_contact }}" type="tel">
					</div> 

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="email_contact">Email de contacto</label>
						<input id="email_contact" class="controls form-control" name="email_contact" value="{{ $data->email_contact }}" type="tel">
					</div> 

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="type">Tipo</label>
						<input id="type" class="controls form-control" name="type" value="{{ $data->type }}" type="tel">
					</div>

					<div class="form-group col-md-12" style="padding-bottom: 1rem;">
						<label for="observations">Observaciones</label> 
						<textarea name="observations" id="observations" cols="10" rows="5" class="controls form-control">{{ $data->observations }}</textarea>
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="status_branch">Status</label>
						<select name="status" id="status_branch" class="form-control">
							<option value="0" @if($data->status == 0) selected @endif>Active</option>
							<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
						</select>
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


