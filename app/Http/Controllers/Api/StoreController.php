<?php namespace App\Http\Controllers\api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Delivery;
use App\Order;
use App\Language;
use App\Text;
use App\User;
use App\Item;
use App\Order_staff;
use DB;
use Validator;
use Redirect;
use Excel;
use Stripe;
class StoreController extends Controller {

	public function homepage()
	{
		$res 	 = new Order;
		$text    = new Text;
		$l 		 = Language::find($_GET['lid']);

		return response()->json([

		'data' 		=> $res->storeOrder(),
		'complete' 	=> $res->storeOrder(5),
		'text'		=> $text->getAppData($_GET['lid']),
		'app_type'	=> isset($l->id) ? $l->type : 0,
		'store'		=> User::find($_GET['id']),
		'overview'	=> $res->overView(),
		'dboy'		=> Delivery::where('status',0)->get()

		]);
	}

	public function getStaffNearby($id)
	{
		$staff = new Delivery;

		return response()->json(['dboy' => $staff->getNearby($id)]);
	}

	public function overview()
	{
		$res 	 = new User;

		return response()->json([
			'data' 		=> $res->overview_app()
		]);
	}

	public function login(Request $Request)
	{
		$res = new User;
		
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

	public function orderProcess()
	{
		$res 		 = Order::find($_GET['id']);
		if ($_GET['status'] == 5) {
			// Buscamos en OrderStaff
			$search_order_Ext = Order_staff::where('order_id',$_GET['id'])->first();
			if ($search_order_Ext) {
				if ($search_order_Ext->count() > 0) {
					$search_order_Ext->delete();
				}
			}
			$staff = Delivery::find($res->d_boy);
			$staff->status_send = 0;
			$staff->save();
			
			$res->status 		= $_GET['status'];
			// Agregamos la comision al repartidor
			$staff = new Delivery;
			$staff->Commset_delivery($_GET['id'],$res->d_boy);
		}else if ($_GET['status'] == 7) {
			$res->type = $_GET['status'];
		}else {
			$res->status 		= $_GET['status'];
		}

		$res->save();
		$staff       = isset($_GET['dboy_Ext']) ? Delivery::find($_GET['dboy_Ext']) : (isset($_GET['dboy'])  ? Delivery::find($_GET['dboy']) : 0);
		if ($staff) {
			$staff->status_send = 1;
			$staff->save();
		}

		if(isset($_GET['dboy']))
		{
			$res->d_boy 		= $_GET['dboy'];
			$res->status_by 	= 1;
			$res->status_time 	= date('d-M-Y').' | '.date('h:i:A');
			$res->save();
			
		}else if (isset($_GET['dboy_Ext'])) {
			
			$res->d_boy 		= $_GET['dboy_Ext'];
			$res->status_by 	= 1;
			$res->status_time 	= date('d-M-Y').' | '.date('h:i:A');
			$res->save();
			
			$chk               = Order_staff::where('order_id',$_GET['id']);
        
			if ($chk) {
				Order_staff::where('order_id',$_GET['id'])->delete();
			}
			
			$add = new Order_staff;
			$add->order_id     = $_GET['id'];
			$add->d_boy        = $_GET['dboy_Ext'];
			$add->status       = 2;
			$add->save();
			
			// if ($_GET['dboy_Ext'] == 2) {
			// 	$order_Staff->addNew($_GET['id'],'update');	
			// }else {
			// 	$order_Staff->addNew($_GET['id'],'add');	
			// }
		}

		$res->sendSms($_GET['id']);
		return response()->json(['data' => $_GET['id']]);
	}

	public function userInfo($id)
	{
		return response()->json(['data' => User::find($id)]);
	}

	public function storeOpen($type)
	{
		$res 		= User::find($_GET['user_id']);
		$res->open 	= $type;
		$res->save();

		return response()->json(['data' => true]);
	}

	public function updateInfo(Request $Request)
	{
		$res 				= User::find($Request->get('id'));
		
		if($Request->get('password'))
		{
			$res->password      = bcrypt($Request->get('password'));
        	$res->shw_password  = $Request->get('password');
		}

		$res->min_cart_value 		 = $Request->get('min_cart_value');
		$res->delivery_charges_value = $Request->get('delivery_charges_value');
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

	public function getItem()
	{
		$res = new User;

		return response()->json(['data' => $res->menuItem($_GET['id'],$_GET['type'],$_GET['value'])]);
	}

	public function changeStatus()
	{
		$res 		 = Item::find($_GET['id']);
		$res->status = $_GET['status'];
		$res->save();

		return response()->json(['data' => true]);
	}
}