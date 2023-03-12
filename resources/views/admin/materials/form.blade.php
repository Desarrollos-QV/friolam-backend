
<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">Información</h1>
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

		<div class="card py-3 m-b-30">
			<div class="card-body">

				<div class="row g-3" style="padding-bottom: 1rem;">
					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="nombre">Nombre</label>
						<input id="nombre" class="controls form-control" name="nombre" value="{{ $data->nombre }}" type="text">
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="densidad">Densidad</label>
						<input id="densidad" class="controls form-control" name="densidad" value="{{ $data->densidad }}" type="text">
					</div>

					<div class="form-group col-md-6" style="padding-bottom: 1rem;">
						<label for="otros">Otros</label>
						<input id="otros" class="controls form-control" name="otros" value="{{ $data->otros }}" type="text">
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
	<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
</div>


