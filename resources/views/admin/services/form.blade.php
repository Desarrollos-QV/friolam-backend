
<div class="row g-3" style="padding-bottom: 1.5rem;">
	<div class="form-group col-md-4">
		<label for="service_name">Nombre del servicio a realizar</label>
		<input type="text" class="form-control" id="service_name" name="service_name" @if(!$data->id) required @endif @if($data->id) value="{{ $data->service_name }}" @endif >
	</div>

	<div class="form-group col-md-4">
		<label>Cliente Principal</label>
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
		<label>SubCliente</label>
		@if($data->id)
		<select name="subclient_id" id="subclient_id" class="form-control" readonly="readonly">
			<option value="{{$data->subclient_id}}">{{$data->sucursal}}</option>
		</select>
		@else 
		<select name="subclient_id" id="subclient_id" class="form-control js-select2">
			
		</select>
		@endif
	</div> 
</div>
 
<div class="row g-3" style="padding-bottom: 1.5rem;">
	<div class="form-group col-md-3">
		<label for="factura"># de factura</label>
		<input type="text" class="form-control" id="factura" name="factura" @if(!$data->id) required @endif @if($data->id) value="{{ $data->factura }}" @endif >
	</div>

	<div class="form-group col-md-3">
		<label for="code_error">Codigo del error</label>
		<input type="text" class="form-control" id="code_error" name="code_error" @if(!$data->id) required @endif @if($data->id) value="{{ $data->factura }}" @endif >
	</div>

	<div class="form-group col-md-6">
		<label for="factura">Trabajador asignado</label>
		<select name="dboy_id" id="dboy_id" class="form-control">
			<option value="0">Sin Asingar aún</option>
			@foreach ($dboys as $db)
				<option value="{{$db->id}}" @if($data->dboy == $db->id) selected @endif>{{$db->name}}</option>
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
	let chargue_wrap = document.querySelector('#subclient_id');
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
			url:'https://mozar38.sg-host.com/api/getSubClients/'+client_id,
			success: function(resp) {  
				console.log(resp);
				let sucursales = document.querySelector('#subclient_id'); 
				 
				// Limpiamos
				for (let i = sucursales.options.length; i >= 0; i--) {
					sucursales.remove(i);
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
			}
		});
	}

	function ChangeBranch(branch)
	{
		let sucursales = document.querySelector('#subclient_id'); 
		 

		let address = sucursales[branch].getAttribute('address');
		let options_pl = document.createElement('option');
		options_pl.value   = sucursales.value;
		options_pl.text    = address; 
	}

</script>