<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">Zonas de cobertura</h1>
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		<div class="card py-3 m-b-30">
			<div class="card-body">
				<div class="row g-3" style="padding-bottom: 1rem;">
					<div class="form-group col-md-6">
						<label for="name">Nombre de la zona</label>
						<input type="text" name="name" id="name" value="{{$data->name}}" class="form-control">
					</div>
		
					<div class="form-group col-md-6">
						<label for="city_id">Ciudad</label>
						<select name="city_id" id="city_id" class="form-select">
							@foreach($citys as $city)
								<option value="{{$city->id}}" @if($city->id == $data->city_id) selected @endif>
									{{$city->name}}
								</option>
							@endforeach
						</select>
					</div>
				</div> 
			</div>
		</div>
	</div>
</div>


<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">
		Zona de cobertura
	</h1>

	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="name">Perimetro de servicio</label>
				<input type="text" readonly id="coverage" class="form-control" value="{{$data->coverage}} km" name="coverage">
			</div>

			<div class="form-group col-md-12" style="margin-top:25px;">
				<label for="name">Selecciona tu zona</label>
				@include('admin.zones.google')
			</div>

			
		</div>
	</div>
</div>
 
<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
