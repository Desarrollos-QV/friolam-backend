<?php

namespace App;

use App\Http\Controllers\NodejsServer;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DB;
class Services extends Authenticatable
{

    protected $table = "services";


    function commaned()
    {
        return $this->hasOne(Commaned::class);
    }


    public function addNew($data,$type) 
    { 
        $add                    = $type === 'add' ? new Services : Services::find($type);
        $add->service_name      = isset($data['service_name']) ? $data['service_name'] : 'indefinido';
        $add->client_id         = isset($data['client_id']) ? $data['client_id'] : 0;
        $add->dboy              = isset($data['dboy_id']) ? $data['dboy_id'] : 0;
        $add->sucursal_id       = isset($data['sucursal_id']) ? $data['sucursal_id'] : 0;
        $add->subclient_id      = isset($data['subclient_id']) ? $data['subclient_id'] : 0;
        $add->code_error        = isset($data['code_error']) ? $data['code_error'] : 0;
        $add->factura           = isset($data['factura']) ? $data['factura'] : 0;
        $add->observations      = isset($data['observations']) ? $data['observations'] : '';

        $add->save();
    }

    public function viewUserComm($id)
    {
        $comm = Commaned::find($id);

        if ($comm->user_id > 0) {
            $user = AppUser::find($comm->user_id);
            if ($user) {
                return $user->name;
            }else {
                return 'No encontrado';
            }
        }else {
            return "No Encontrado";
        }
    }

    public function viewClientComm($id)
    {
        $comm = Admin::find($id);

        if ($comm->id != 1) {
            return $comm->name;
        }else {
            return "No Encontrado";
        }
    }

    public function viewMaterial($id)
    {
        $mat = Materials::find($id);

        if ($mat->id) {
            return $mat->nombre;
        }else {
            return "No Encontrado";
        }
    }

    public function viewDboy($id)
    {
        $serv = Services::find($id);

        if ($serv->id) {
            if ($serv->dboy != 0) {
                $dboy = AppUser::find($serv->dboy);
                return ($dboy) ? $dboy->name : "No Encontrado";
            }else {
                return "Sin Asignar";
            }
        }else {
            return "No Encontrado";
        }
    }
    
    public function viewPlL($id)
    {
        $req = PlaceLoading::find($id);
        return $req->name;
    }
    
    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($status)
    {
        $admin = Auth::guard('admin')->user();
        return Services::where(function($query) use($status,$admin) { 
            if ($status == 0) {
                $query->whereIn('services.status',[0,1]);
            }else {
                $query->where('services.status',$status);
            }

            // Filtramos por administrador
            if ($admin->id != 1 || $admin->perm != 'All') {
                $query->where('services.client_id',$admin->id); 
            }

        })->leftjoin('users','users.id','=','services.client_id')
            ->leftjoin('sub_users','sub_users.id','=','services.subclient_id')
            ->select('users.name as name_user','users.*','sub_users.razon_social as subcliente','services.*')
            ->orderBy('services.id','DESC')->with('commaned')->get();
    }

    /*
    |--------------------------------------
    |Get Element data from db
    |--------------------------------------
    */
    public function getElement($id)
    {
        return Services::where('services.id',$id)->leftjoin('users','users.id','=','services.client_id')
        ->select('users.name as name_user','users.*','services.*')
        ->orderBy('services.id','DESC')->first();
    }
    
    
    /*
    |--------------------------------------
    |Report Elemento from BD
    |--------------------------------------
    */
    public function getReport($data)
    {
        $res = Services::where(function($query) use($data) {

            if(isset($data['from']))
            {
                $from = date('Y-m-d',strtotime($data['from']));
            }
            else
            {
                $from = null;
            }

            if(isset($data['to']))
            {
                $to = date('Y-m-d',strtotime($data['to']));
            }
            else
            {
                $to = null;
            }

            if($from)
            {
                $query->whereDate('services.created_at','>=',$from);
            }

            if($to)
            {
                $query->whereDate('services.created_at','<=',$to);
            }

            if($data['client_id'])
            {
                $query->where('services.client_id',$data['client_id']);
            }

        })->orderBy('services.id','ASC')->get();

        $allData = [];

        foreach($res as $row)
        {
            
            /** Obtenemos el cliente */
            $client_id = Admin::find($row->client_id);
            if ($client_id) {
                $client = $client_id->name;
            }else {
                $client = 'No encontrado';
            }

            /** Obtenemos el repartidor */
            if ($row->dboy != 0) {
                $dboy_id = Delivery::find($row->dboy);
                if ($dboy_id) {
                    $dboy = $dboy_id->name;
                }else {
                    $dboy = "No encontrado";
                }
            }else {
                $dboy = "No asignado";
            }

            /** Obtenemos la PLACA del vehiculo */
            if ($row->dboy != 0) {
                $dboy_dr = Delivery::find($row->dboy)->type_driver;
                $placa_id = Vehicles::find($dboy_dr);
                if ($placa_id) {
                    $placa = $placa_id->number_plate;
                }else {
                    $placa = "Placa no encontrada";
                }
            }else {
                $placa = "Placa no asignada";
            }


            /** Obtenemos el material */
            $material_id = Materials::find($row->material_id);
            if ($material_id) {
                $material = $material_id->nombre;
            }else {
                $material = "No encontrado";
            }

            /** Obtenemos la sucursal  */
            $sucursal_id = Branchs::find($row->sucursal_id);
            if ($sucursal_id) {
                $sucursal = $sucursal_id->name;
            }else {
                $sucursal = "No encontrado";
            }

            $allData[] = [
                'id'        => $row->id,
                'dboy'      => $dboy, 
                'placa'     => $placa,
                'date'      => $row->created_at,//date('d-M-Y H:M:S',strtotime($row->created_at)),
                'client'    => $client,
                'material'  => $material,
                'sucursal'  => $sucursal,
                'cargue'    => ($row->chargue_id != 0) ? $row->address_origin : 'Sin asignar',
                'descargue' => ($row->descharg_id != 0) ? $row->address_destin : 'Sin asignar',
                'factura'   => $row->factura,
                'costos'    => $row->d_charges,
                'cantidad_c' => $row->amount_chargue,
                'obs'       => $row->observations
            ];
        }

        return $allData;
    }


    /**
     * 
     * Obtenemos todos los Servicios de este usuario que esten activos 
     * 
    */
    function chkServices($id)
    {
        $req = Services::where(function($query) use($id){
            $query->whereIn('services.dboy',[$id,0]);
            $query->whereIn('services.status',[0,1,3,4,4.5,5]);
        })->orderBy('id','DESC')
        ->get();
        
        $data = []; 
        foreach ($req as $key) {
            
            $data[] = [
                'work' => ($key->dboy != 0) ? collect(AppUser::find($key->dboy))->except(['password','pswfacebook','otp','created_at','updated_at']) : [],
                'service' => [
                    "id"            => $key->id,
                    "service_name"  => $key->service_name,
                    "client"        => (isset($key->client_id)) ? collect(User::find($key->client_id))->except(['password','shw_password','remember_token','lat','lng','subusers','s_data','created_at','updated_at']) : [],
                    "subclient"  => (isset($key->subclient_id)) ? collect(SubUsers::find($key->subclient_id))->except(['updated_at','created_at']) : [],
                    "code_error"    => $key->code_error,
                    "factura"	    => $key->factura,
                    "observations"  => $key->observations,
                    "status"        => $key->status,
                    "created_at"	=> date('d-M-Y',strtotime($key->created_at))   
                ]
            ];
        }

        return $data;
    }
}