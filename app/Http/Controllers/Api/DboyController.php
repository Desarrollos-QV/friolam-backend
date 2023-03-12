<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Delivery;
use App\Order;
use App\Commaned;
use App\Language;
use App\Order_staff;
use App\Text;
use App\Admin;
use App\User;
use DB;
use Validator;
use Redirect;
use Excel;
use Stripe;
class DboyController extends Controller {

	public function homepage()
	{
		$res 	 = new Commaned;
		$text    = new Text;

		return response()->json([

			'data' 		=> $res->history_ext(0),
			'events' 	=> $res->history_staff(0),
			'text'		=> $text->getAppData($_GET['lid']),
			'admin'		=> Admin::find(1)
		
		]);
	}

	public function homepage_ext()
	{
		try {
			$res 	 = new Commaned;
			$text    = new Text; 
			$Neworder = Order_staff::where('d_boy',$_GET['id'])->whereIn('status',[0])->count();
			$Ruteorder = Order_staff::where('d_boy',$_GET['id'])->whereIn('status',[1,4.5])->count();

			return response()->json([
				'data' 		=> $res->history_ext(0),
				'events' 	=> $res->history_staff(0),
				'Neworder'  => $Neworder,
				'Ruteorder' => $Ruteorder,
				'text'		=> $text->getAppData($_GET['lid']),
				'app_type'	=> isset($l->id) ? $l->type : 0,
				'admin'		=> Admin::find(1)	
			]);
		} catch (\Exception $th) {
			return response()->json(['data' => 'error', 'error' => $th->getMessage()]);
		}
	}

	public function overview()
	{
		$res 	 = new Delivery;

		return response()->json([
			'data' 		=> $res->overview(),
			'admin'		=> Admin::find(1),
		]);
	}


	public function staffStatus($type)
	{
		$res 			= Delivery::find($_GET['user_id']);
		$res->status 	= $type;
		$res->save();

		return response()->json(['data' => true]);
	}

	public function login(Request $Request)
	{
		$res = new Delivery;
		
		return response()->json($res->login($Request->all()));
	}

	public function forgot(Request $Request)
	{
		$res = new AppUser;
		
		return response()->json($res->forgot($Request->all()));
	}

	public function verify(Request $Request)
	{
		$res = new AppUser;
		
		return response()->json($res->verify($Request->all()));
	}

	public function updatePassword(Request $Request)
	{
		$res = new AppUser;
		
		return response()->json($res->updatePassword($Request->all()));
	}

	public function startRide()
	{
		try {
			$res 		 = Commaned::find($_GET['id']);
			$res->status = $_GET['status'];
			$res->save();
			
			// El pedido ha sido aceptado
			if ($_GET['status'] == 1) {
				// Verificamos que el pedido no ha sido tomado por alguien mas
				if ($res->d_boy != 0) {
					return response()->json(['data' => 'inUse']);	
				}else {
					$res->d_boy  = $_GET['d_boy'];
					$res->save();

					// Notificamos al usuario que el repartidor acepto el pedido
					app('App\Http\Controllers\Controller')->sendPush("Repartidor en camino","El repartidor ha aceptado el pedido y va en camino a recolectarlo.",$res->user_id);
					
					// Marcamos al repartidor ocupado.
					$staff = Delivery::find($res->d_boy);
					$staff->status_send = 0;
					$staff->save();

					// Eliminamos toda la info de la tabla repas
					Order_staff::where('event_id',$_GET['id'])->delete();

					// Registramos al repartidor asignado
					$order_Ext = new Order_staff;
					$order_Ext->event_id 	= $_GET['id'];
					$order_Ext->d_boy 		= $_GET['d_boy'];
					$order_Ext->type 		= 1;
					$order_Ext->status 		= '1';
					$order_Ext->save();
				}
				
			}else if ($_GET['status'] == 4.5) {
				// Notificamos al usuario que su pedido va en camino.
				app('App\Http\Controllers\Controller')->sendPush("Pedido recolectado","Tu Pedido ha sido recolectado y esta en ruta al destino!!😃",$res->user_id);

				$order_Ext = Order_staff::where('event_id',$_GET['id'])->first();
				$order_Ext->status = 4.5;
				$order_Ext->save();

				$res->save();

			}else if ($_GET['status'] == 5) {
				Order_staff::where('event_id',$_GET['id'])->delete();
				
				$staff = Delivery::find($res->d_boy);
				$staff->status_send = 0;
				$staff->save();

				// Guardamos la imagen
				$pic = "delivery_".$_GET['id'].".jpg";
				$res->pic_end_order = $pic;
				$res->save();

				// Agregamos la comision al repartidor
				$staff = new Delivery;
				$staff->Commset_delivery($res->id,$_GET['d_boy']);
				// Notificamos al usuario
				app('App\Http\Controllers\Controller')->sendPush("Pedido entregado","🎉Entregamos tu pedido🎉😃, ayudanos recomendandonos, no te olvides de calificar al repartidor y 🏡 #QuedateEnCasa 🏡",$res->user_id);
			}
			
			return response()->json(['data' => 'done']);
		} catch (\Exception $th) {
			return response()->json(['data' => 'fail', 'err' => $th->getMessage()]);	
		}
	}

	public function rejected(Request $Request)
	{
		// Reiniciamos el pedido
		$id 		 = $Request->get('id');
		$res 		 = Order::find($id);

		$res->d_boy 		= 0;
		$res->status 		= 1;
		$res->status_by 	= 1;
		$res->status_time 	= date('d-M-Y').' | '.date('h:i:A');
		$res->save();
		
		// Damos entre a otro Repartidor
		$chk               = Order_staff::where('order_id',$id);
	
		if ($chk) {
			Order_staff::where('order_id',$id)->delete();
		}
		
		$add = new Order_staff;
		$add->order_id     = $id;
		$add->d_boy        = 0;
		$add->status       = 1;
		$add->save();

		$msg = "Por favor vuelve Reasigna el pedido a otro repartidor.";
		$title = "El Repartidor no pudo aceptar el pedido.";
		// Comercios app
		app('App\Http\Controllers\Controller')->sendPushS($title,$msg,$res->store_id);

		return response()->json(['data' => 'done']);
			
	}

	public function userInfo($id)
	{
		$count = Commaned::where('d_boy',$id)->where('status',6)->count();

		return response()->json(['data' => Delivery::find($id),'order' => $count]);
	}

	
	public function updateInfo(Request $Request)
	{
		$res 				= Delivery::find($Request->get('id'));
		$res->password      = bcrypt($Request->get('password'));
        $res->shw_password  = $Request->get('password');
		$res->save();

		return response()->json(['data' => true]);
	}

	public function updateLocation(Request $Request)
	{
		if($Request->get('user_id') > 0)
		{
			$add 			= Delivery::find($Request->get('user_id'));
			$add->lat 		= $Request->get('lat');
			$add->lng 		= $Request->get('lng');
			$add->save();
		}

		return response()->json(['data' => true]);
	}


	public function getPolylines()
	{
		$url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$_GET['latOr'].",".$_GET['lngOr']."&destination=".$_GET['latDest'].",".$_GET['lngDest']."&mode=driving&key=".Admin::find(1)->ApiKey_google;
		$max      = 0;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);


		$request = json_decode($output, true);
		
		return response()->json($request);
	}

	public function chkNotify()
	{
		$content = ["en" => "Prueba de audio, Notificaciones Push"];
		$head 	 = ["en" => "Notificacion Comercios"];		

	
		$fields = array(
		'app_id' => "ca6cf39d-b0f7-49ce-aa12-e624b6bd8a9d",
		'included_segments' => array('All'),	
		// 'filters' => [$daTags],
		'data' => array("foo" => "bar"),
		'contents' => $content,
		'headings' => $head,
		'android_channel_id' => '80321c11-2ef0-4c8a-813b-7456492d3db9'
		);
		
		
		$fields = json_encode($fields);
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Basic YmNkODEyM2YtYWE4OS00MGI1LWI2ZGEtYjJjOGVhYjdiNDk1'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);

		

		$req = json_decode($response, TRUE);
       
	    return response()->json(['data' => $req]);
	}

	public function uploadpic_order()
	{
		//Rename and move the file to the destination folder 
		// Input::file('file')->move($destinationPath,$newImageName);
		
		$target_path = "upload/user/delivery/";

		$target_path = $target_path .basename($_FILES['file']['name']);
    
		move_uploaded_file($_FILES['file']['tmp_name'], $target_path);

		return response()->json(['data' => "echo"]);
	}

	public function notifyClient(Request $Request)
	{
		$user = $Request->get('user_id');
		
		$req = new NodejsServer;
		
		return response()->json([
			'data' => app('App\Http\Controllers\Controller')->sendPush("Tu pedido esta en tu domicilio.","HOLA! TIENE 7 MINUTOS PARA RECIBIR SU PEDIDO, DE LO CONTRARIO EL REPARTIDOR YA NO ESTARÁ OBLIGADO A ESPERAR. GRACIAS",$user),
			'req' => $req->notifyClient($Request->all()) 
		]);
	}

	public function rateComm_event(Request $Request)
	{
		try {
			$type = $Request->get('type_order');

			$req = new Commaned;
			return response()->json(['data' => $req->rateComm_event($Request->all())]);
		} catch (\Exception $e) {
			return response()->json(['data' => 'fail','err' => $e->getMessage()]);
		}
		
	}
}