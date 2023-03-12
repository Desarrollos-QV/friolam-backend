<?php

namespace App;

use App\Http\Controllers\NodejsServer;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Auth;
use DB;
class Commaned extends Authenticatable
{
    protected $table = "commaned";

    public function addNew($data) 
    {
        $add                    = new commaned;
        $add->user_id           = isset($data['user_id']) ? $data['user_id'] : '';
        $add->address_origin    = isset($data['address_origin']) ? $data['address_origin'] : '';
        $add->lat_orig          = isset($data['lat_orig']) ? $data['lat_orig'] : 0;
        $add->lng_orig          = isset($data['lng_orig']) ? $data['lng_orig'] : 0;
        $add->address_destin    = isset($data['address_destin']) ? $data['address_destin'] : '';
        $add->lat_dest          = isset($data['lat_dest']) ? $data['lat_dest'] : 0;
        $add->lng_dest          = isset($data['lng_dest']) ? $data['lng_dest'] : 0;
        $add->first_instr       = isset($data['first_instr']) ? $data['first_instr'] : '';
        $add->second_instr      = isset($data['second_instr']) ? $data['second_instr'] : '';
        $add->d_boy             = 10; //isset($data['d_boy']) ? $data['d_boy'] : 0;
        $add->price_comm        = isset($data['price_comm']) ? $data['price_comm'] : 0;
        $add->d_charges         = isset($data['d_charges']) ? $data['d_charges'] : 0;
        $add->total             = isset($data['total']) ? $data['total'] : 0;
        $add->payment_method    = isset($data['payment_method']) ? $data['payment_method'] : 0;
        $add->payment_id        = isset($data['payment_id']) ? $data['payment_id'] : 0;
        $add->status            = 1; //isset($data['status']) ? $data['status'] : 0;

        // Antes de guardar Verificamos si el metodo de pago es consaldo
        if ($add->payment_method == 2) {
            $usr = AppUser::find($add->user_id);
            // Verificamos si cuenta con el saldo suficiente
            if ($usr->saldo > $add->total) { // Si contamos con saldo disponible 
                $add->save();
            
                // Quitamos saldo
                $usr = AppUser::find($add->user_id);

                $saldo = ($usr->saldo - $add->total);

                $usr->saldo = $saldo;
                $usr->save();

                // Comenzamos la solicitud de repartidores
                // $req = new NodejsServer;
                // $data = [
                //     'id_order' => $add->id
                // ];
                
                // $req->NewOrderComm($data);
 
                // Notificamos al usuario que el repartidor acepto el pedido
                app('App\Http\Controllers\Controller')->sendPush("Repartidor en camino","El repartidor ha aceptado el pedido y va en camino a recolectarlo.",$add->user_id);
                
                // Marcamos al repartidor ocupado.
                $staff = Delivery::find(10);
                $staff->status_send = 0;
                $staff->save();

                // Eliminamos toda la info de la tabla repas
                Order_staff::where('event_id',$add->id)->delete();

                // Registramos al repartidor asignado
                $order_Ext = new Order_staff;
                $order_Ext->event_id 	= $add->id;
                $order_Ext->d_boy 		= 10;
                $order_Ext->type 		= 1;
                $order_Ext->status 		= '1';
                $order_Ext->save();
                // Retornamos hecho
                return ['data' => 'done'];
            }else { // No contamos con saldo
                return ['data' => 'fail','msg' => "balance_insuficient"];
            }
        }else {
            $add->save();
            // Comenzamos la solicitud de repartidores
            // $req = new NodejsServer;
            // $data = [
            //     'id_order' => $add->id
            // ];
            
            // $req->NewOrderComm($data); 
          
            // Notificamos al usuario que el repartidor acepto el pedido
            app('App\Http\Controllers\Controller')->sendPush("Repartidor en camino","El repartidor ha aceptado el pedido y va en camino a recolectarlo.",$add->user_id);
            
            // Marcamos al repartidor ocupado.
            $staff = Delivery::find(10);
            $staff->status_send = 0;
            $staff->save();

            // Eliminamos toda la info de la tabla repas
            Order_staff::where('event_id',$add->id)->delete();

            // Registramos al repartidor asignado
            $order_Ext = new Order_staff;
            $order_Ext->event_id 	= $add->id;
            $order_Ext->d_boy 		= 10;
            $order_Ext->type 		= 1;
            $order_Ext->status 		= '1';
            $order_Ext->save();
            // Retornamos hecho
            return ['data' => 'done'];
        }
    }

    public function getIva($costs_ship)
    {
        $admin = Admin::find(1);

        $iva_amount      = 0;
        $iva_amount_type = $admin->iva_type; // Cargos de iva de la plataforma
        $iva_amount_value = $admin->iva_value; // Cargos de iva de la plataforma
        // Comision + IVA 
        if ($iva_amount_type == 0) { // Valor en %
            $iva_amount = ($costs_ship * $iva_amount_value) / 100;
        }

        return $iva_amount;
    }

    /*
    |--------------------------------------
    |Actualizacion de instrucciones
    |--------------------------------------
    */
    public function updateComm($data,$id)
    {
        $add                    = commaned::find($id);
        $add->first_instr       = isset($data['first_instr']) ? $data['first_instr'] : '';
        $add->second_instr      = isset($data['second_instr']) ? $data['second_instr'] : '';

        $add->save();

        return true;
    }

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($status)
    {
        return commaned::where(function($query) use($status){

            if ($status == 1) {
                $query->whereIn('commaned.status',[1,4.5]);
            }else {
                $query->where('commaned.status',$status);
            }

        })->leftjoin('app_user','app_user.id','=','commaned.user_id')
            ->select('app_user.name as name_user','app_user.*','commaned.*')
            ->orderBy('commaned.id','DESC')->get();
    }
    

    /*
    |--------------------------------------
    |Get Element data from db
    |--------------------------------------
    */
    public function getElement($id)
    {
        return commaned::where('commaned.id',$id)
            ->leftjoin('app_user','app_user.id','=','commaned.user_id')
            ->select('app_user.name as name_user','app_user.*','commaned.*')
            ->orderBy('commaned.id','DESC')->get();
    }

    public function viewDboyComm($id)
    {
        $comm = Commaned::find($id);

        if ($comm->d_boy > 0) {
            $dboy = Delivery::find($comm->d_boy);
            if ($dboy) {
                return $dboy->name;
            }else {
                return 'No encontrado';
            }
        }else {
            return "Sin asignar";
        }
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
 

    /**
     * 
     * Obtenemos costos de envio por repartos
     * 
    */

    function Costs_shipKM($data)
    {
        
        $admin = Admin::find(1);
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".
        $data['lat_orig'].",".
        $data['lng_orig'].
        "&destinations=".$data['lat_dest'].",".
        $data['lng_dest'].
        "&key=".$admin->ApiKey_google;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);


        $request = json_decode($output, true);

        // Obtenemos la ciudad en la que se encuenra
        $city               = City::find($data['city_id']);
        if (isset($city->id)) {
            $min_distance       = $city->min_distance; // Distancia minima del servicio
            $type_value         = $city->c_type; // Tipo del valor KM/Fijo
            $value              = $city->c_value; // Valor de la comision
            $min_value          = $city->min_value; // Valor por el minimo del servicio
            $distance = 0; // Distancia de un punto a otro
            $service = 0; // Status del servicio
            $costs_ship = 0; // Costos de envio
            $times_delivery = '0 mins'; // Tiempos de entrega


            if($request['status'] == 'OK') {
                $km_inm = $request['rows'][0]['elements'][0]['distance']['value'];
                $times_delivery = $request['rows'][0]['elements'][0]['duration']['text'];

                $distance = ($km_inm / 1000); // lo convertimos a decimales
                
                $service = 1; // Si hay servicio
                $km_extra   = ($distance - $min_distance);
                $value_ext  = ($type_value == 0) ? ($km_extra * $value) : ($km_extra + $value);
                $costs_ship = ($min_value + $value_ext);
            }

            
            return [
                'service'      => $service,
                'costs_ship'    => round($costs_ship,2),
                'duration'      => $times_delivery,
                'distance'      => round($distance,2),
                'request' => $request
            ];
        }else {
            return [
                'service'      => 0,
                'costs_ship'    => 0,
                'duration'      => "0 min",
                'distance'      => 0,
            ];
        }
    }

    /**
     * 
     * Obtenemos listado de repartidores mas cercanos 
     * 
    */
    public function getNearby($event_id)
    {
        // Obtenemos el arreglo de los repartidores
        $staff       = Delivery::where('status',0)->get(); // que este activo
                        
        // Obtenemos las coordenadas de entrega
        $order       = Commaned::find($event_id);
        
        // Seteamos el mensaje
        $msg2 = "Nuevo servicio de reparto, Ingresa para más información";
        
        $data  = [];
        foreach ($staff as $key) {
            // Obtenemos lat & lng de cada repa
            $lat = $key->lat;
            $lon = $key->lng;

            // Verificamos que esten bien
            if ($lat != null || $lat !='' && $lot != null || $lon !='') {        
                // Comparamos las coordenadas entre el repartidor y el punto de recoleccion del pedido
                // $res  = Commaned::where('id',$event_id)
                //     ->select(DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                //     * cos(radians(commaned.lat_orig)) 
                //     * cos(radians(commaned.lng_orig) - radians(" . $lon . ")) 
                //     + sin(radians(" .$lat. ")) 
                //     * sin(radians(commaned.lat_orig))) AS distance_order"),'commaned.*')
                //     ->orderBy('id','DESC')->get();

                $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=kilometers&origins=".
                $lat.",".
                $lon."&destinations=".
                $order->lat_orig.",".
                $order->lng_orig.
                "&key=".Admin::find(1)->ApiKey_google;
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec ($ch);
                $info = curl_getinfo($ch);
                $http_result = $info ['http_code'];
                curl_close ($ch);
        
        
                $request = json_decode($output, true);
                
                $max_distance = 0;
                $max      = 0;
                
                if($request['status'] == 'OK') {
                    if ($request['rows'][0]['elements'][0]['status'] == 'OK') {
                        $km_inm = intval(str_replace('km','',$request['rows'][0]['elements'][0]['distance']['value']));
                        
                        $max_distance = round($km_inm / 1000,2);
                        
                        //Obtenemos la distancia de cada repa al punto A
                        $distancia_total = $max_distance;
                        // Si la distancia maxima del repa es mayor a 0 procedemos 
                        if ($key->max_range_km > 0) {
                            // Si la distancia maxima de entrega entra en el rango entramos
                            if ($distancia_total <= $key->max_range_km) {
                            
                                $data[] = [
                                    'max_range_km' => $key->max_range_km,
                                    'distancia_total' => $distancia_total,
                                    'km_inm' => $km_inm,
                                    'dboy' => $key->id,
                                    'name' => $key->name,
                                    'request' => $request
                                ];   
                            //    // Registramos y notificamos
                            //    $staff_ext = new Order_staff;
                            //    $staff_ext->order_id = $order_id;
                            //    $staff_ext->d_boy    = $key->id;
                            //    $staff_ext->status   = 0;
                            //    $staff_ext->save();
    
                            //    // Notificamos
                            //    app('App\Http\Controllers\Controller')->sendPushD("Nuevo pedido recibido",$msg2,$key->id);
                            };
                        };
                    }
                }   
            } 
        }

        return [
            'dboys' => $data,//$this->ORDER_ASC_STAFF($data)
            'url' =>  $url
        ];
    }

    function ORDER_ASC_STAFF($data)
    {
        foreach ($data as $key => $row) {
            $aux[$key] = $row['distancia_total'];
        }

        array_multisort($aux, SORT_ASC, $data);

        return $data;
    }

    /**
     * 
     * Seteamos el repartidor enviado por el servidor NODEJS 
     * 
    */

    function setStaffEvent($event_id,$dboy_id)
    {
        
        // Checamos si el pedido ya fue tomado
        $event = Commaned::find($event_id);

        if ($event->d_boy != 0) {
            return [
                'status' => 'in_rute'
            ];
        }else {
            // Seteamos la tabla
            Order_staff::where('event_id',$event_id)->delete();

            // Guardamos el Nuevo
            $order = new Order_staff;

            $order->event_id = $event_id;
            $order->d_boy    = $dboy_id;
            $order->type     = 1; // 0 = Food Delivery & 1 = Delivery Box
            $order->status   = 0;
            $order->save();
 
            // Notificamos al repartidor
			app('App\Http\Controllers\Controller')->sendPushD("Nuevo servicio","Nueva solicitud de reparto, revisa los detalles.",$dboy_id);

            return [
                'status' => 'not_rute'
            ];
        }
    }

    /**
     * 
     * Eliminamos al no tener respuesta de algun repartidor 
     * 
    */

    function delStaffEvent($event_id)
    {
        // Seteamos la tabla
        Order_staff::where('event_id',$event_id)->delete();

        // Marcamos como repartidor no encontrado status = 3
        $event = Commaned::find($event_id);
        
        $event->status = 3;
        $event->save();

        // Notificamos al usuario que no se encontro repartidores
        $msg = "No hemos encontrado un repartidor disponible para tu solicitud, por favor vuelve a intentarlo";
        $title = "No encontramos repartidores!!";
        app('App\Http\Controllers\Controller')->sendPush($title,$msg,$event->user_id);
        
        return [
            'status' => 'done'
        ];
    }

    /**
     * 
     * Obtenemos el historial completo 
     * 
    */

    public function history($id)
    {
       $data     = [];
       $currency = Admin::find(1)->currency;
 
       $orders = Commaned::where(function($query) use($id){
 
          if($id > 0)
          {
             $query->where('commaned.user_id',$id);
          }
 
          if(isset($_GET['status']))
          {
             if($_GET['status'] == 3 || $_GET['status'] == 3.5)
             {
                $query->whereIn('commaned.status',[3,3.5,4]);
             }
             else
             {
                $query->where('commaned.status',5);
             }
          }
 
       })->join('delivery_boys','commaned.d_boy','=','delivery_boys.id')
          ->select('commaned.*','delivery_boys.name as dboy')
          ->orderBy('id','DESC')
          ->get();
 
       
       foreach($orders as $order)
       {
          
          if($order->status == 0)
          {
            $status = "Pendiente";
          }
          elseif($order->status == 1)
          {
            $status = "Confirmada";
          }
          elseif($order->status == 2)
          {
            $status = "Cancelada";
          }
          elseif($order->status == 3)
          {
            $status = "Repartidor no encontrado";
          }
          elseif($order->status == 4)
          {
            $status = "Elegido para entregar por ".$order->dboy;
          }
          elseif($order->status == 5)
          {
            $status = "Pedido entregado";
          }
          elseif($order->status == 6)
          {
            $status = "Pedido entregado";
          }
          else
          {
             $status = "Sin estatus";
          }
 
          $countRate = Rate::where('event_id',$order->id)->where('user_id',$id)->first();
          $tot_com   = $order->total - $order->d_charges;
 
          $data[] = [
 
             'id'        => $order->id,
             'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
             'total'     => $order->total,
             'dboy'      => ($order->d_boy > 0) ? Delivery::find($order->d_boy) : [],
             'tot_com'   => $tot_com, 
             'd_charges' => $order->d_charges,
             'status'    => $status,
             'st'        => $order->status,
             'hasRating' => isset($countRate->id) ? $countRate->star : 0,
             'ratStaff'  => isset($countRate->staff_id) ? $countRate->staff_id : 0,
             'event'      => $order,
             'pay'       => $order->payment_method
          ];
       }
 
       return $data;
    }

    public function history_staff($id)
    {
       $data     = [];
       $currency = Admin::find(1)->currency;
 
       $orders = Commaned::where(function($query) use($id){
 
          if(isset($_GET['id']))
          {
             $query->where('commaned.d_boy',$_GET['id']);
          }
 
          $query->whereIn('commaned.status',[0,1,2,3,4,4.5,5,6]);
 
       })->join('delivery_boys','commaned.d_boy','=','delivery_boys.id')
          ->select('commaned.*','delivery_boys.name as dboy')
          ->orderBy('id','DESC')
          ->get();
 
       
       foreach($orders as $order)
       {
          
          if($order->status == 0)
          {
            $status = "Pendiente";
          }
          elseif($order->status == 1)
          {
            $status = "Confirmada";
          }
          elseif($order->status == 2)
          {
            $status = "Cancelada";
          }
          elseif($order->status == 3)
          {
            $status = "Repartidor no encontrado";
          }
          elseif($order->status == 4)
          {
            $status = "Elegido para entregar por ".$order->dboy;
          }
          elseif($order->status == 5)
          {
            $status = "Pedido entregado";
          }
          elseif($order->status == 6)
          {
            $status = "Pedido entregado";
          }
          else
          {
             $status = "Sin estatus";
          }
 
          $countRate = Rate::where('event_id',$order->id)->where('staff_id',$_GET['id'])->first();
          $tot_com   = $order->total - $order->d_charges;
 
          $data[] = [
 
             'id'        => $order->id,
             'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
             'total'     => $order->total,
             'tot_com'   => $tot_com, 
             'd_charges' => $order->d_charges,
             'status'    => $status,
             'st'        => $order->status,
             'hasRating' => isset($countRate->id) ? $countRate->star : 0,
             'ratStaff'  => isset($countRate->staff_id) ? $countRate->staff_id : 0,
             'event'      => $order,
             'pay'       => $order->payment_method
          ];
       }
 
       return $data;
    }

    public function history_ext($id)
   {
      $data     = [];
      $currency = Admin::find(1)->currency;

      $orders = Order_staff::where(function($query) use($id){

         if(isset($_GET['id']))
         {
            $query->whereIn('orders_staff.d_boy',[$_GET['id']]);
         }

         if(isset($_GET['status']))
         {
            if($_GET['status'] == 1)
            {
               $query->whereIn('orders_staff.status',[0,1,2,3,4,4.5]);
            }
         }

      })->get();

      if ($orders->count() > 0) {

         foreach($orders as $pedido)
         {
            // Es un mandadito
            $order = Commaned::find($pedido->event_id);

            $items = [];
            
            if($order->status == 0)
            {
                $status = "Buscando repartidor";
            }
            elseif($order->status == 1)
            {
                $status = "Confirmado";
            }
            elseif($order->status == 2)
            {
                $status = "Cancelada";
            }
            elseif($order->status == 3)
            {
                $status = "Repartidor no encontrado";
            }
            elseif($order->status == 4)
            {
                $status = "Pedido recolectado";
            }
            elseif($order->status == 4.5)
            {
                $status = "Pedido en camino";
            }
            else
            {
                $status = "Entregado en ".$order->status_time;
            }

            $countRate = Rate::where('event_id',$order->id)->where('staff_id',$id)->first();
            $tot_com   = $order->total - $order->d_charges;

            $data[] = [
                'type'      => 'comanded',
                'id'        => $order->id,
                'user'      => AppUser::find($order->user_id),
                'date'      => date('d-M-Y',strtotime($order->created_at))." | ".date('h:i:A',strtotime($order->created_at)),
                'total'     => $order->total,
                'd_charges' => $order->d_charges,
                'tot_com'   => $tot_com, //$i->RealTotal($order->id),
                'st'        => $order->status,
                'stime'     => $order->status_time,
                'sid'       => $order->user_id,
                'hasRating' => isset($countRate->id) ? $countRate->star : 0,
                'currency'  => $currency,
                'pay'       => $order->payment_method,
                'comm'      => $order
            ];
            
         }
      }
      return $data;
   }

    /**
     * 
     * Obtenemos todos los eventos de este usuario que esten activos 
     * 
    */
    function chkEvents_comm($id)
    {
        $req = commaned::where(function($query) use($id){
            $query->where('commaned.user_id',$id);
            $query->whereIn('commaned.status',[0,1,3,4,4.5,5]);
        })->orderBy('id','DESC')
        ->get();
        
        $data = [];

        foreach ($req as $key) {
            
            $data[] = [
                'dboy' => ($key->d_boy != 0) ? Delivery::find($key->d_boy) : [],
                'event' => $key
            ];
        }

        return $data;
    }

    /**
     * 
     * Obtenemos toda la info de este servicio unico
     * 
    */
    function chk_comm($id)
    {
        $req = commaned::find($id)->first();
        
        $data = [
            'dboy' => ($req->d_boy != 0) ? Delivery::find($req->d_boy) : [],
            'event' => $req
        ];

        return $data;
    }

    /**
     * 
     * Cancelamos el pedido por parte del usuario 
     * 
    */

    function cancelComm_event($event_id)
    {
        
        $req = Commaned::find($event_id);

        $req->status = 2;
        $req->save();

        // Seteamos la tabla
        Order_staff::where('event_id',$event_id)->delete();

        return [
            'status' => 'done'
        ];
        
    }

    /**
     * 
     * Calificamos el servicio 
     * 
    */

    function rateComm_event($data)
    {
        $add = new Rate;
        // Agregamos nuevo
        if (isset($data['user_id'])) {
            $add->user_id     = $data['user_id'];
            $add->staff_id    = $data['d_boy'];
            $add->event_id    = $data['oid'];
            $add->star        = $data['star'];
            $add->comment_staff     = isset($data['comment']) ? $data['comment'] : '';
            $add->good_attention = isset($data['good_attention']) ? 1 : 0;
            $add->efficient_delivery = isset($data['efficient_delivery']) ? 1 : 0;
            
            $add->save();

            // Marcamos como calificado
            $req = commaned::find($data['oid']);

            $req->status = 6;
            $req->save();

            // Notificamos
            $msg = "El usuario ha calificado tu servicio con ".$data['star'].' estrellas.';
            $title = "Te han calificado por tu servicio.";
            app('App\Http\Controllers\Controller')->sendPushD($title,$msg,$data['d_boy']);
            
            return ['data' => true];
        }else {
            $add->staff_id    = $data['d_boy'];
            $add->event_id    = $data['oid'];
            $add->star        = $data['star'];
            $add->comment_staff     = isset($data['comment']) ? $data['comment'] : '';
            $add->good_attention = isset($data['good_attention']) ? 1 : 0;
            
            
            $add->save();            
            return ['data' => true];
        }
    }

    /**
     * 
     * Obtenemos Reporte de Servicios 
     * 
    */

    public function getReport($data)
    {
       $res = Commaned::where(function($query) use($data) {
 
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
             $query->whereDate('commaned.created_at','>=',$from);
          }
 
          if($to)
          {
             $query->whereDate('commaned.created_at','<=',$to);
          }
 
       })->orderBy('commaned.id','ASC')->get();
 
       $allData = [];
 
       foreach($res as $row)
       {
 
            // ID
            // Usuario
            // Email
            // Repartidor
            // Origen
            // Destino
            // Cargos de envio
            // Cargos de IVA
            // Total
            // Metodo de pago
            // Imagen de entrega
            // Estatus del pedido.

            // Obtenemos el usuario
            $user = User::find($row->user_id);

            // Obtenemos el repartidor
            $staff = Delivery::find($row->d_boy);

            $allData[] = [
                'id'     => $row->id,
                'date'   => $row->created_at,
                'user'   => isset($user) ? $user->name : 'Indefinido.',
                'email'  => isset($user) ? $user->email : 'Indefinido.',
                'staff'  => isset($staff) ? $staff->name : 'Indefinido',
                'origin' => isset($row->address_origin) ? $row->address_origin : 'Indefinido',
                'destin' => isset($row->address_destin) ? $row->address_destin : 'Indefinido',
                'd_charges' => $row->d_charges,
                'total'  => $row->total,
                'payment_method' => $row->payment_method,
                'pic_order' => Asset('upload/order/delivery/'.$row->pic_end_order),
                'status' => $row->status
            ];
       }
 
       return $allData;
    }
}
