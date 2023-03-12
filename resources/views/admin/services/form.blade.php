<input type="hidden" name="order_store" vale="false">
<input type="hidden" name="store_id" value="0">

<div class="row g-3" style="padding-bottom: 1.5rem;">
	<div class="form-group col-md-4">
		<label>Cliente</label>
		@if($data->id)
			<select name="client_id" id="client_id" class="form-control" readonly="readonly">
				<option value="{{$data->client_id}}">{{$data->name_user}}</option>
			</select>
		@else 
			<select name="client_id" id="client_id" class="form-control">
				@foreach ($clients as $us)
					<option value="{{$us->id}}">{{$us->name}}</option>
				@endforeach 
			</select>
		@endif
	</div>

	<div class="form-group col-md-4">
		<label>Sucursal</label>
		@if($data->id) 
		<select name="sucursal_id" id="sucursal_id" class="form-control" readonly="readonly">
			<option value="{{$data->sucursal_id}}">{{$data->sucursal}}</option>
		</select>
		@else 
		<select name="sucursal_id" id="sucursal_id" class="form-control">
			
		</select>
		@endif
	</div> 
 
	<div class="form-group col-md-4">
		<label>Material a cargar</label>
		@if($data->id) 
		<select name="material_id" id="material_id" class="form-control" readonly="readonly">
			<option value="{{$data->material_id}}">{{$data->material}}</option>
		</select>
		@else 
		<select name="material_id" id="material_id" class="form-control">
			@foreach ($materials as $mt)
				<option value="{{$mt->id}}">{{$mt->nombre}}</option>
			@endforeach 
		</select>
		@endif
	</div> 
</div>

<div class="row g-3" style="padding-bottom: 1.5rem;">
	<div class="form-group col-md-6">
		<label for="d_charges">Costos del servicio</label>
		<input type="text" name="d_charges" id="d_charges" class="form-control" @if(!$data->id) required @endif  @if($data->id) value="{{ $data->d_charges }}" @endif >
	</div>

	<div class="form-group col-md-6">
		<label for="amount_chargue">Cantidad cargada</label>
		<input type="text" name="amount_chargue" id="amount_chargue" class="form-control" @if(!$data->id) required @endif @if($data->id) value="{{ $data->amount_chargue }}" @endif >
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1.5rem;">
	<div class="form-group col-md-6">
		<label for="factura"># de factura</label>
		<input type="text" class="form-control" id="factura" name="factura" @if(!$data->id) required @endif @if($data->id) value="{{ $data->factura }}" @endif >
	</div>

	<div class="form-group col-md-6">
		<label for="factura">Conductor asignado</label>
		<select name="dboy_id" id="dboy_id" class="form-control">
			<option value="0">Sin Asingar aún</option>
			@foreach ($dboys as $db)
				<option value="{{$db->id}}" @if($data->dboy == $db->id) selected @endif>{{$db->name}}</option>
			@endforeach 
		</select>
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1.5rem;">
	<div class="form-group col-md-6">
		<label for="address_origin">
			Lugar de carga
		</label> 
		<select name="chargue_id" id="chargue_id" class="form-control"> 
		</select>
	</div>

	<div class="form-group col-md-6">
		<label for="address_destin">
			Lugar de descargue
		</label>
		
		<select name="descharg_id" id="descharg_id" class="form-control">
			<option value="">Sin asignar</option>
			@foreach ($descharg as $pl)
				<option value="{{$pl->id}}" @if($data->descharg_id == $pl->id) selected @endif>{{$pl->address}}</option>
			@endforeach 
		</select>
	</div>
</div>

<div class="row g-3" style="padding-bottom: 1.5rem;"> 
	<div class="form-group col-md-12">
		<label for="factura">Observaciones</label>
		<textarea name="observations" id="observations" cols="10" rows="8" class="controls form-control">{{ $data->observations }}</textarea>
	</div>
</div>


<script>

	// Obtenemos las sucursales de este cliente
	let client_wrap = document.querySelector('#client_id'); 
	let chargue_wrap = document.querySelector('#sucursal_id');
	ChangeClient(client_wrap[0].value);
    client_wrap.addEventListener('change', (event) => {
      ChangeClient(event.target.value);
    });
	chargue_wrap.addEventListener('change', (event) => {
      ChangeBranch(event.target.selectedIndex);
    });

	function ChangeClient(client_id)
	{
		$.ajax({
			async: true,
			type:'GET',
			url:'https://safe-logistic.desarrollos-qv.com/api/getBranchs/'+client_id,
			success: function(resp) {  
				let sucursales = document.querySelector('#sucursal_id'); 
				let chargue_pl = document.querySelector('#chargue_id');
				// Limpiamos
				for (let i = sucursales.options.length; i >= 0; i--) {
					sucursales.remove(i);
				}

				for (let i = chargue_pl.options.length; i >= 0; i--) {
					chargue_pl.remove(i);
				}
				// Agregamos
				resp.forEach(element => {
					let option = document.createElement('option'); 
					option.value = element.id;
					option.text  = element.name; 
					option.setAttribute('address',element.address); 
					sucursales.appendChild(option);
				});

				// Agregamos la direcion de la sucursal asignada
				let address = sucursales[sucursales.selectedIndex].getAttribute('address'); 
				let options_pl = document.createElement('option');
				options_pl.value   = sucursales.value;
				options_pl.text    = address;
				chargue_pl.appendChild(options_pl);
			}
		});
	}

	function ChangeBranch(branch)
	{
		let sucursales = document.querySelector('#sucursal_id'); 
		let chargue_pl = document.querySelector('#chargue_id');
		for (let i = chargue_pl.options.length; i >= 0; i--) {
			chargue_pl.remove(i);
		}

		let address = sucursales[branch].getAttribute('address');
		let options_pl = document.createElement('option');
		options_pl.value   = sucursales.value;
		options_pl.text    = address;
		chargue_pl.appendChild(options_pl);
	}

</script>