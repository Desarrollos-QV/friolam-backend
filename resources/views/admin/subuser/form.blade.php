<div class="row g-3">
    <div class="col-md-6">
        <label for="client_basis"># de Cliente Basis</label>
        {!! Form::text('client_basis',null,['required' => 'required','placeholder' => 'Cliente Basis','class' => 'form-control','id' => 'client_basis'])!!}
    </div>

    <div class="col-md-6">
        <label for="client_sap"># de Cliente SAP</label>
        {!! Form::text('client_sap',null,['required' => 'required','placeholder' => 'Cliente SAP','class' => 'form-control','id' => 'client_sap'])!!}
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label for="rut">RUT</label>
        {!! Form::text('rut',null,['required' => 'required','placeholder' => 'RUT','class' => 'form-control','id' => 'rut'])!!}
    </div> 

    <div class="col-md-6">
        <label for="razon_social">Razon Social</label>
        {!! Form::text('razon_social',null,['required' => 'required','placeholder' => 'Razon Social','class' => 'form-control','id' => 'razon_social'])!!}
    </div> 
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label for="direccion">Direccion</label>
        {!! Form::text('direccion',null,['required' => 'required','placeholder' => 'Direccion','class' => 'form-control','id' => 'direccion'])!!}
    </div> 

    <div class="col-md-3">
        <label for="phone">Telefono (whatsapp)</label>
        {!! Form::text('phone',null,['required' => 'required','placeholder' => 'Número','class' => 'form-control','id' => 'phone'])!!}
    </div> 
    
    <div class="col-md-3">
        <label for="numero">Número</label>
        {!! Form::text('numero',null,['required' => 'required','placeholder' => 'Número','class' => 'form-control','id' => 'numero'])!!}
    </div> 
</div>


<div class="row g-3">
    

    <div class="col-md-6">
        <label for="comuna">Comuna</label>
        {!! Form::text('comuna',null,['required' => 'required','placeholder' => 'Comuna','class' => 'form-control','id' => 'comuna'])!!}
    </div> 

    <div class="col-md-3">
        <label for="canal">Canal</label>
        {!! Form::text('canal',null,['required' => 'required','placeholder' => 'Canal','class' => 'form-control','id' => 'canal'])!!}
    </div> 

    <div class="col-md-3">
        <label for="subcanal">SubCanal</label>
        {!! Form::text('subcanal',null,['required' => 'required','placeholder' => 'SubCanal','class' => 'form-control','id' => 'subcanal'])!!}
    </div> 
</div>


<div class="row g-3">
    <div class="col-md-6">
        <label for="comuna">Ciclo</label>
        {!! Form::text('comuna',null,['required' => 'required','placeholder' => 'Ciclo','class' => 'form-control','id' => 'comuna'])!!}
    </div> 

    <div class="col-md-3">
        <label for="canal2">Canal</label>
        {!! Form::text('canal2',null,['required' => 'required','placeholder' => 'Canal','class' => 'form-control','id' => 'canal2'])!!}
    </div> 
    
    <div class="col-md-3">
        <label for="subcanal2">SubCanal</label>
        {!! Form::text('subcanal2',null,['required' => 'required','placeholder' => 'SubCanal','class' => 'form-control','id' => 'subcanal2'])!!}
    </div> 
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label for="inputEmail4">Status</label>
        <select name="status" class="form-select">
            <option value="0" @if($data->status == 0) selected @endif>Active</option>
            <option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
        </select>
    </div>
 
</div> 
