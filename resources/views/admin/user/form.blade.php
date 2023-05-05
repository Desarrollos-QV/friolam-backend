  
    @if($data->logo)
        <img src="{{Asset('upload/user/logo/'.$data->logo)}}" 
        style="position:absolute;
        max-height:150px;
        top:-60px;
        right:0px;
        border-radius:15px;
        z-index:1;" class="d-none d-lg-block">
    @endif

    @if($data->img)
        <img src="{{Asset('upload/user/'.$data->img)}}" width="100px" 
        style="position:absolute;
        top:40px;
        right:15px;
        border-radius:15px;
        z-index:5;" class="d-none d-lg-block">
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label for="img">Imagen de perfil (recomenada 600px * 400px)</label>
            <input type="file" name="img" id="img" class="form-control" @if(!$data->id) required="required" @endif>
        </div>

        <div class="col-md-6">
            <label for="logo">Imagen de portada (recomenada 720px * 315px)</label>
            <input type="file" name="logo" id="logo" class="form-control" @if(!$data->id) required="required" @endif>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label for="inputEmail6">Nombre</label>
            {!! Form::text('name',null,['required' => 'required','placeholder' => 'Name','class' => 'form-control'])!!}
        </div>

        <div class="col-md-6">
            <label for="inputEmail4">Email (<i>This will be username</i>)</label>
            {!! Form::email('email',null,['required' => 'required','placeholder' => 'Email Address','class' => 'form-control'])!!}
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="inputEmail6">Telefono</label>
            {!! Form::text('phone',null,['required' => 'required','placeholder' => 'Contact Number','class' => 'form-control'])!!}
        </div> 

        <div class="col-md-6">
            <label for="inputEmail4">Status</label>
            <select name="status" class="form-select">
                <option value="0" @if($data->status == 0) selected @endif>Active</option>
                <option value="1" @if($data->status == 1) selected @endif>Disbaled</option>
            </select>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-md-6">
            <label for="inputEmail4">Agrega SubClientes</label>
            <select name="subusers[]" class="form-select js-select2" multiple="true">
                @foreach ($SubUsers as $subs)
                    <option value="{{$subs->id}}" @if(in_array($subs->id,$arraySubUsers)) selected @endif>
                        {{ $subs->razon_social }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($data->id)
        <div class="col-md-6">
            <label for="inputEmail6">Cambia la contraseña (<small>Ingrese una nueva contraseña si desea cambiar la actual.</small>)</label>
            <input type="Password" value="{{$data->shw_password}}" name="password" class="form-control">
        </div>
        @else
        <div class="col-md-6">
            <label for="inputEmail6">Contraseña</label>
            <input type="Password" name="password" class="form-control" @if(!$data->id) required="required" @endif>
        </div>   
        @endif
    </div> 
   