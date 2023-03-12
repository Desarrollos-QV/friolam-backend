
@if($row->status == 0)

    <form action="{{url($form_url)}}" method="post" enctype="multipart/form-data" target="_blank">
        @csrf
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acciones
        </button>
        <input type="hidden" name="staff_id" value="{{$row->id}}">
        <input type="hidden" name="type_report" value="excel">
        <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" 
            data-popper-placement="bottom-start">
            <li>
                <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                    <i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Ver solicitud</a>
            </li>
            <li>
                <a href="{{ Asset(env('admin').'/commanedStatus?id='.$row->id.'&status=2') }}" onclick="return confirm('Estas Seguro(a)?')" class="dropdown-item">
                    <i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Cancelar Servicio</a>
            </li>
            <li>
                <button type="button" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Servicio" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')">
                    <i class="lni lni-trash"></i>&nbsp;&nbsp;&nbsp; Eliminar </button>
            </li>
        </ul>
    </form>

@elseif($row->status == 2)

    <span style="font-size: 12px">Cancelado a las <br>{{ $row->updated_at }}</span>

@elseif($row->status == 6)
    <form action="{{url($form_url)}}" method="post" enctype="multipart/form-data" target="_blank">
        @csrf
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acciones
        </button>
        <input type="hidden" name="staff_id" value="{{$row->id}}">
        <input type="hidden" name="type_report" value="excel">
        <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" 
            data-popper-placement="bottom-start">
            <li>
                <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                    <i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Ver solicitud</a>
            </li>
            <li>
                <a href="{{ Asset('upload/order/delivery/'.$row->pic_end_order) }}" target="_blank" class="dropdown-item">
                    <i class="fadeIn animated bx bx-id-card"></i> &nbsp;&nbsp;&nbsp; Ver Imagen de entregado</a>
            </li>
        </ul>
    </form>
@else

    <form action="{{url($form_url)}}" method="post" enctype="multipart/form-data" target="_blank">
        @csrf
        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acciones
        </button>
        <input type="hidden" name="staff_id" value="{{$row->id}}">
        <input type="hidden" name="type_report" value="excel">
        <ul class="dropdown-menu" style="margin: 0px; position: absolute; inset: 0px auto auto 0px; transform: translate(0px, 38px);" 
            data-popper-placement="bottom-start">
            <li>
                <a href="{{ Asset($link.$row->id.'/edit') }}" class="dropdown-item">
                    <i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Ver solicitud</a>
            </li>
            <li>
                <a href="{{ Asset(env('admin').'/commanedStatus?id='.$row->id.'&status=2') }}" onclick="return confirm('Estas Seguro(a)?')" class="dropdown-item">
                    <i class="lni lni-pencil"></i> &nbsp;&nbsp;&nbsp; Cancelar Servicio</a>
            </li>
            <li>
                <button type="button" class="dropdown-item" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Servicio" onclick="deleteConfirm('{{ Asset($link."delete/".$row->id) }}')">
                    <i class="lni lni-trash"></i>&nbsp;&nbsp;&nbsp; Eliminar </button>
            </li>
        </ul>
    </form>
@endif