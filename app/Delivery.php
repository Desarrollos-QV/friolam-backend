<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DB;
class Delivery extends Authenticatable
{
    protected $table = "delivery_boys";

    /*
    |----------------------------------------------------------------
    |   Validation Rules and Validate data for add & Update Records
    |----------------------------------------------------------------
    */

    public function rules($type)
    {
        if($type === 'add')
        {
            return [

            'phone' => 'required|unique:delivery_boys',

            ];
        }
        else
        {
            return [

            'phone'     => 'required|unique:delivery_boys,phone,'.$type,

            ];
        }
    }

    public function validate($data,$type)
    {

        $validator = Validator::make($data,$this->rules($type));
        if($validator->fails())
        {
            return $validator;
        }
    }

    /*
    |--------------------------------
    |Create/Update city
    |--------------------------------
    */

    public function addNew($data,$type)
    {

        $add                    = $type === 'add' ? new Delivery : Delivery::find($type);
        $add->city_id           = isset($data['city_id']) ? $data['city_id'] : 0;
        $add->name              = isset($data['name']) ? $data['name'] : null;
        $add->phone             = isset($data['phone']) ? $data['phone'] : null;
        $add->type_driver       = isset($data['type_edriver']) ? $data['type_edriver'] : 0;
       
        
        if(isset($data['licence']))
        {
            $filename   = time().rand(111,699).'.' .$data['licence']->getClientOriginalExtension(); 
            $data['licence']->move("upload/driver/", $filename);   
            $add->licence           = $filename;
        }
        
        if(isset($data['carnet']))
        {
            $filename   = time().rand(111,699).'.' .$data['carnet']->getClientOriginalExtension(); 
            $data['carnet']->move("upload/driver/", $filename);   
            $add->carnet           = $filename;
        }

        $add->rfc               = isset($data['rfc']) ? $data['rfc'] : null;
        $add->status            = isset($data['status']) ? $data['status'] : 0;

        if(isset($data['password']))
        {
            $add->password      = bcrypt($data['password']);
            $add->shw_password  = $data['password'];
        }

        $add->save();
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($store = 0)
    {
        return Delivery::where(function($query) use($store) {

        })->leftjoin('city','delivery_boys.city_id','=','city.id')
          ->select('city.name as city','delivery_boys.*')
          ->orderBy('delivery_boys.id','DESC')->get();
    }

    /*
    |--------------------------------------
    |Login To
    |--------------------------------------
    */
    public function login($data)
    {
     $chk = Delivery::where('status',0)->where('phone',$data['phone'])->where('shw_password',$data['password'])->first();

     if(isset($chk->id))
     {
        return [
            'msg' => 'done',
            'user_id' => $chk->id,
            'user_type' => $chk->store_id
        ];
     }
     else
     {
        return ['msg' => 'Opps! Detalles de acceso incorrectos'];
     }
    }

    /*
    |--------------------------------------
    |Get Report
    |--------------------------------------
    */
    public function getReport($data)
    {
        $res = Delivery::where(function($query) use($data) {

            if($data['staff_id'])
            {
                $query->where('delivery_boys.id',$data['staff_id']);
            }

        })->join('services','delivery_boys.id','=','services.dboy')
        ->select('services.*','delivery_boys.*')
        ->orderBy('delivery_boys.id','ASC')->get();

       $allData = [];

       foreach($res as $row)
       {
  
            $allData[] = [
                'id'                => $row->id,
                'name'              => $row->name,
                'rfc'               => $row->rfc,
                'email'             => $row->email, 
                'platform_porcent'  => $row->price_comm,
                'type_staff_porcent'=> ($row->c_type_staff == 0) ? 'Valor Fijo' : 'valor en %',
                'staff_porcent'     => $row->c_value_staff,
                'total'             => $row->total
            ];
       }

       return $allData;
    }

    /*
    |--------------------------------------
    |Get all data from db for Charts
    |--------------------------------------
    */
    public function overView()
    {
        // 

        $admin = new Admin;

        return [
            'total'     => Commaned::where('d_boy',$_GET['id'])->count(),
            'complete'  => Commaned::where('d_boy',$_GET['id'])->where('status',6)->count(),
            'canceled'  => Commaned::where('d_boy',$_GET['id'])->where('status',2)->count(),
            'saldos'    => $this->saldos($_GET['id']),
            'x_day'     => [
                'tot_orders' => Commaned::where('d_boy',$_GET['id'])->whereDate('created_at','LIKE','%'.date('m-d').'%')->count(),
                'amount'     => $this->chartxday($_GET['id'],0,1)['amount']
            ],
            'day_data'     => [
                'day_1'    => [
                'data'  => $this->chartxday($_GET['id'],2,1),
                'day'   => $admin->getDayName(2)
                ],
                'day_2'    => [
                'data'  => $this->chartxday($_GET['id'],1,1),
                'day'   => $admin->getDayName(1)
                ],
                'day_3'    => [
                'data'  => $this->chartxday($_GET['id'],0,1),
                'day'   => $admin->getDayName(0)
                ]
            ],
            'week_data' => [
                'total' => $this->chartxWeek($_GET['id'])['total'],
                'amount' => $this->chartxWeek($_GET['id'])['amount']
            ],
            'month'     => [
                'month_1'     => $admin->getMonthName(2),
                'month_2'     => $admin->getMonthName(1),
                'month_3'     => $admin->getMonthName(0),
            ],
            'complet'   => [
                'complet_1'    => $this->chart($_GET['id'],2,1)['order'],
                'complet_2'    => $this->chart($_GET['id'],1,1)['order'],
                'complet_3'    => $this->chart($_GET['id'],0,1)['order'],
            ],
            'cancel'   => [
                'cancel_1'    => $this->chart($_GET['id'],2,1)['cancel'],
                'cancel_2'    => $this->chart($_GET['id'],1,1)['cancel'],
                'cancel_3'    => $this->chart($_GET['id'],0,1)['cancel']
            ]
        ];
    }

    public function saldos($id)
    {
        // Saldos y Movimientos
        $discount = 0;
        $cargos   = 0;
        $ventas   = 0;
        $comm     = 0;
        
        $staff      = Delivery::find($id);
        $saldo      = $staff->amount_acum;
        $order_day  = Commaned::where(function($query) use($id){

            $query->where('d_boy',$id);

        })->where('status',6)->get();

        $sum   = Commaned::where(function($query) use($id){

            $query->where('d_boy',$id);

        })->where('status',6)->sum('d_charges');

        if ($order_day->count() > 0) {
            $comm = ($sum * $staff->c_value_staff) / 100;
            $ventas = $ventas + ($sum - $comm);
        }

        return [
            'Saldo'      => $saldo,
            'ventas'     => $ventas
        ];
    }
    

    public function chart($id,$type,$sid = 0)
    {
        $month      = date('Y-m',strtotime(date('Y-m').' - '.$type.' month'));

            $order   = Commaned::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

            })->where('status',6)->whereDate('created_at','LIKE',$month.'%')->count();


            $cancel  = Commaned::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

            })->where('status',2)->whereDate('created_at','LIKE',$month.'%')->count();

            return ['order' => $order,'cancel' => $cancel];
    }

    public function chartxday($id,$type,$sid = 0)
    {
        $admin = new Admin;
        $date_past = strtotime('-'.$type.' day', strtotime(date('Y-m-d')));
        $day = date('m-d', $date_past);

        $comm = 0;
        $amount = 0;
        $debt  = 0 ;
        $ventas = 0;

        $order   = Commaned::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

        })->where('status',6)->whereDate('created_at','LIKE','%'.$day.'%')->count();


        $cancel  = Commaned::where(function($query) use($sid,$id){

                if($sid > 0)
                {
                    $query->where('d_boy',$id);
                }

        })->where('status',2)->whereDate('created_at','LIKE','%'.$day.'%')->count();


        if ($type == 0) {
            $staff          = Delivery::find($id);
           
            $sum   = Commaned::where(function($query) use($id){

                $query->where('d_boy',$id);

            })->where('status',6)
                ->whereDate('created_at','LIKE','%'.$day.'%')->sum('d_charges');

            
            $comm = ($sum * $staff->c_value_staff) / 100;
            $ventas = $ventas + ($sum - $comm);
        }

        return [
            'order' => $order,
            'cancel' => $cancel,
            'amount' => $ventas
        ];
    }

    public function chartxWeek($id)
    {
            $date = strtotime(date("Y-m-d"));
            $ventas = 0;
            $init_week = strtotime('last Sunday');
            $end_week  = strtotime('next Saturday');

            $total   = Commaned::where(function($query) use($id){

                $query->where('d_boy',$id);

            })->where('status',6)
                ->where('created_at','>=',date('Y-m-d', $init_week))
                ->where('created_at','<=',date('Y-m-d', $end_week))->count();

            $sum   = Commaned::where(function($query) use($id){

                $query->where('d_boy',$id);

            })->where('status',6)
                ->where('created_at','>=',date('Y-m-d', $init_week))
                ->where('created_at','<=',date('Y-m-d', $end_week))->sum('d_charges');

            $dboy = Delivery::find($id);

            $comm = ($sum * $dboy->c_value_staff) / 100;
            $ventas = $ventas + ($sum - $comm);

            return [
                'total'   => $total,
                'amount'  => $ventas,
                'lastday' => date('Y-m-d', $init_week),
                'nextday' => date('Y-m-d', $end_week)
            ];
    }

    /*
    |--------------------------------------
    |Add Comm
    |--------------------------------------
    */

    public function add_comm($data,$id)
    {
        $staff = Delivery::find($id);
        if ($data['pay_staff'] > $staff->amount_acum) {
            return 'mount_sup';
        }else {
            $acum  = round($staff->amount_acum - $data['pay_staff'],0);
            $staff->amount_acum = $acum;
            $staff->save();
            return true;
        }
        
    }

    public function Commset_delivery($order_id,$d_boy_id)
    {   
        $order          = Commaned::find($order_id);
        $staff          = Delivery::find($d_boy_id);
        $c_value_staff  = $staff->c_value_staff; // 10
        
        $delivery_charges = $order->d_charges; // 86
       
        $comm_admin   = ($delivery_charges * $c_value_staff) / 100; // 8.6
        
        $amount_acum = $staff->amount_acum + $comm_admin;

        $staff->amount_acum = $amount_acum;
        $staff->save();

        return true;
    }

    /*
    |--------------------------------------
    |Get Nearby
    |--------------------------------------
    */

    public function getNearby()
    {
        $staff = Delivery::where('store_id',0)->where('status',0)->get();
		$max_distance = Admin::find(1)->max_distance_staff; // Rango maximo

        $data  = [];
        foreach ($staff as $key) {
            $lat = $key->lat; // Lat Driver
            $lon = $key->lng; // Lng Driver

            $user_lat = isset($_GET['lat']) ? $_GET['lat'] : 0;
            $user_lng = isset($_GET['lng']) ? $_GET['lng'] : 0;

            if ($lat != null || $lat !='' && $lot != null || $lon !='') {          
                $res  = DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                    * cos(radians(".$user_lat.")) 
                    * cos(radians(".$user_lng.") - radians(" . $lon . ")) 
                    + sin(radians(" .$lat. ")) 
                    * sin(radians(".$user_lat."))) AS distance")->get();
                   
                    $data[] = [
                        'data' => $res
                    ];
               
            } 
        }

        return [
            'data' => $data,
            'lat'  => $_GET['lat']
        ];
    }
}
