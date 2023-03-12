<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">Rangos y comisiones</h1>
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		<div class="card py-3 m-b-30">
			<div class="card-body">
				<div class="row g-3" style="padding-bottom: 1rem;">
					<div class="form-group col-md-6">
						<label for="inputEmail6">Cobro por envio</label>
						<input type="text" name="c_value" value="{{$data->c_value}}" class="form-control">
					</div>
		
					<div class="form-group col-md-6">
						<label for="inputEmail6">Tipo de Cobro</label>
						<select name="c_type" class="form-select">
							<option value="0" @if($data->c_type == 0) selected
								@endif>Valor por KM</option>
							<option value="1" @if($data->c_type == 1) selected
								@endif>Valor fijo</option>
						</select>
					</div>
				</div>

				<div class="row g-3" style="padding-bottom: 1rem;">
					<div class="form-group col-md-6">
						<label for="min_distance">Distancia minima de Servicio
							<small>(Distancia en KM de 0 a )</small> </label>
						<input type="text" name="min_distance" value="{{$data->min_distance}}" class="form-control">
					</div>

					<div class="form-group col-md-6">
						<label for="min_value">Cobro por el Minimo de servicio
							<small>(Valor Fijo en $)</small> </label>
						<input type="text" name="min_value" value="{{$data->min_value}}"
								class="form-control">
					</div>

				</div>

				<div class="row g-3" style="padding-bottom: 1rem;">
					<div class="form-group col-md-6">
						<label for="inputEmail6">Distancia maxima de servicio</label>
						<input type="number" name="max_distance" class="form-control" value="{{$data->max_distance}}">
					</div>

					<div class="form-group col-md-6">
						<label for="inputEmail6">Status</label>
						<select name="status" class="form-control">
							<option value="0" @if($data->status == 0) selected @endif>Active</option>
							<option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">Ubicación de la ciudad</h1>
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		<div class="form-row">
			@include('admin.city.google')
		</div>
	</div>
</div>

@if(count($zones) > 0)
<div class="tab-content" id="myTabContent1">
	<h1 style="font-size: 20px">Zonas de cobertura</h1>
	<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
		<div class="card py-3 m-b-30">
			<div class="card-body">
				<div class="table-responsive">
					<table id="example" class="table mb-0" style="width:100%">
						<thead class="table-dark">
							<tr>
								<th>Zona</th>
								<th>Cobertura</th>
								<th>Status</th>  
								<th style="text-align: right">Opciones</th>
							</tr> 
						</thead>
						<tbody>
							@foreach ($zones as $zn)
							<tr>
								<td>{{$zn->name}}</td>
								<td>{{round($zn->coverage,2)}} KM</td> 
								<td>
									@if($zn->status == 0)
										<button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-success"
											onclick="confirmAlert('{{ Asset(env('admin').'/zones/'.'status/'.$zn->id) }}')">Active</button>
									@else
										<button type="button" class="btn btn-sm m-b-15 ml-2 mr-2 btn-danger"
											onclick="confirmAlert('{{ Asset(env('admin').'/zones/'.'status/'.$zn->id) }}')">Disabled</button>
									@endif
								</td>

								<td style="text-align: right">
									<button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acciones
									</button>
									<ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" data-popper-placement="bottom-start">
										<li><a href="{{ Asset(env('admin').'/zones/'.$zn->id.'/edit') }}" class="dropdown-item"><i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Editar</a></li>
										<li><button class="dropdown-item" type="button" data-toggle="tooltip" data-placement="top"    data-original-title="Delete This Entry"
											onclick="deleteConfirm('{{ Asset(env('admin').'/zones/'."delete/".$zn->id) }}')"><i class="lni lni-trash"></i>&nbsp;&nbsp;&nbsp; Eliminar </button>
										</li>
									</ul>
								</td>
							</tr> 
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endif

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button>
