
<div class="tab-content" id="myTabContent1">


<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

<h4>Acerca de</h4>
<div class="card py-3 m-b-30">
<div class="card-body">
<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail6">Descripción</label>
<textarea id="summernote" name="about_us">{!! $data->about_us !!}</textarea>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail6">Imagen <small>(Opcional)</small></label>
<input type="file" name="about_img" class="form-control">

@if($data->about_img)

<br><img src="{{ Asset('upload/page/'.$data->about_img) }}" height="60"> 

<a href="{{ Asset($form_url.'/add?remove=about_img') }}" onclick="return confirm('Are you sure?')" style="color:red">Remove</a>

@endif

</div>
</div>

</div>
</div>

<h4>Como Trabajamos</h4>
<div class="card py-3 m-b-30">
<div class="card-body">
<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail6">Descripción</label>
<textarea id="summernote2" name="how">{!! $data->how !!}</textarea>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail6">Imagen <small>(Opcional)</small></label>
<input type="file" name="how_img" class="form-control" @if(!$data->id) required="required" @endif>

@if($data->how_img)

<br><img src="{{ Asset('upload/page/'.$data->how_img) }}" height="60">

<a href="{{ Asset($form_url.'/add?remove=how_img') }}" onclick="return confirm('Are you sure?')" style="color:red">Remove</a>

@endif

</div>
</div>

</div>
</div>

<h4>Preguntas Frecuentes</h4>
<div class="card py-3 m-b-30">
<div class="card-body">
<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail6">Descripción</label>
<textarea id="summernote3" name="faq">{!! $data->faq !!}</textarea>
</div>
</div>

</div>
</div>

<h4>Sección Contáctanos</h4>
<div class="card py-3 m-b-30">
<div class="card-body">
<div class="form-row">
<div class="form-group col-md-12">
<label for="inputEmail6">Descripción</label>
<textarea id="summernote4" name="contact_us">{!! $data->contact_us !!}</textarea>
</div>
</div>

</div>
</div>
</div>
</div>

<button type="submit" class="btn btn-success btn-cta">Guardar Cambios</button><br><br>
