<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\SubUsers;
use App\City;
use App\Branchs;
use App\UserImage;
use App\Admin;
use App\CategoryStore;
use App\Opening_times;
use App\Addon;
use App\Item;
use App\ItemAddon;
use App\Rate;
use DB;
use Validator;
use Redirect;
use IMS;
use Stripe;

class UserController extends Controller {

	public $folder  = "admin/user.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{					 
		$res = new User;

		return View($this->folder.'index',[
			'data' => $res->getAll(),
			'link' => env('admin').'/user/',
			'currency' => Admin::find(1)->currency,
			'cats'  => [],
			'admin' => new Admin
		]);
	}	
	
	/*
	|---------------------------------------
	|@Add new page
	|---------------------------------------
	*/
	public function show()
	{		
		return View($this->folder.'add',[
			'data' 		=> new User,
			'SubUsers'   => SubUsers::get(),
			'arraySubUsers'	=> [],
			'form_url'  => env('admin').'/user',
			'admin' => new Admin
		]);
	}
	
	/*
	|---------------------------------------
	|@Save data in DB
	|---------------------------------------
	*/
	public function store(Request $Request)
	{			
		$data = new User;

		// return response()->json($Request->All());

		if($data->validate($Request->all(),'add'))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),'add'))->withInput();
			exit;
		}

		$data->addNew($Request->all(),"add");
		return redirect(env('admin').'/user')->with('message','New Record Added Successfully.');
	}
	
	/*
	|---------------------------------------
	|@Edit Page 
	|---------------------------------------
	*/
	public function edit($id)
	{				
		$user = new User;

		return View($this->folder.'edit',[
			'data' 		=> User::find($id),
			'form_url'  => env('admin').'/user/'.$id,
			'admin'		=> Admin::find(1),
			'SubUsers'   => SubUsers::get(),
			'arraySubUsers'	=> $user->getAssignedBranch($id),
		]);
	}

	
	
	/*
	|---------------------------------------
	|@update data in DB
	|---------------------------------------
	*/
	public function update(Request $Request,$id)
	{	
		$data = new User;
		
		if($data->validate($Request->all(),$id))
		{
			return redirect::back()->withErrors($data->validate($Request->all(),$id))->withInput();
			exit;
		}

		$data->addNew($Request->all(),$id);
		
		return redirect(env('admin').'/user')->with('message','Record Updated Successfully.');
	}
	
	/*
	|---------------------------------------------
	|@Delete Data
	|---------------------------------------------
	*/
	public function delete($id)
	{
		User::where('id',$id)->delete();
		
		return redirect(env('admin').'/user')->with('message','Record Deleted Successfully.');
	}

	/*
	|---------------------------------------------
	|@Change Status
	|---------------------------------------------
	*/
	public function status($id)
	{
		$res 			= User::find($id);
		
		if(isset($_GET['type']) && $_GET['type'] == "trend")
		{
			$res->trending 	= $res->trending == 0 ? 1 : 0;
		}
		elseif(isset($_GET['type']) && $_GET['type'] == "open")
		{
			$res->open 	= $res->open == 0 ? 1 : 0;
		}else {
			$res->status = $res->status == 0 ? 1 : 0;
		}

		$res->save();

		return redirect(env('admin').'/user')->with('message','Status Updated Successfully.');
	}

	public function imageRemove($id)
	{
		UserImage::where('id',$id)->delete();

		return redirect::back()->with('message','Deleted Successfully.');
	}

	public function loginWithID($id)
	{
		if(Auth::loginUsingId($id))
		{
		   return Redirect::to('home')->with('message', 'Welcome ! Your are logged in now.');	
		}
		else
		{
			return Redirect::to('login')->with('error', 'Something went wrong.');
		}
		
	}

	public function ViewTime($id)
	{
		$op_time 	= new Opening_times;

		$res        = $op_time->	ViewTime($id);

		return $res;
	}

	/*
	|---------------------------------------------
	| Vista de rating de usuarios
	|--------------------------------------------
	*/

	public function rate($id)
    {
        $admin = new Admin;
		$rate  = new Rate;
		return View(
		$this->folder.'rate',
		[
			'data' 		=> User::find($id),
			'rate_data' => $rate->GetRate($id),
		]
		);
	}

	/*
	|---------------------------------------------
	| Vista de codigos QR
	|--------------------------------------------
	*/
	
	public function viewqr($id)
	{ 
		return View(
		$this->folder.'viewqr',
		[
			'data' 		=> User::find($id),
			'admin'		=> Admin::find(1)
		]
		);
	}

	/*
	|---------------------------------------------
	|@Add Pay
	|---------------------------------------------
	*/

	public function pay($id)
	{
		$admin = new Admin;

		if ($admin->hasperm('Pagos Negocios')) {

			return View(
				$this->folder.'pay',
				[
					'data' => User::find($id),
					'form_url' => env('admin').'/user_pay/'.$id,
					'link' => env('admin').'/user/',
					'currency' => Admin::find(1)->currency
				]
				);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Pagos');
		}
	}

	public function payAll($id)
	{
		$stpre = User::find($id);
		$stpre->saldo = 0;
		$stpre->save();

		return Redirect::to(env('admin').'/user')->with('message', 'Saldo restablecido con exito.');
	}

	public function user_pay(Request $Request,$id)
	{
		$staff = new User;
		$new_saldo = $Request->get('new_saldo');

		$req = $staff->add_saldo($Request->All(),$id);
		if ($req == true) {
			return redirect(env('admin').'/user')->with('message','Se ha han depositado $'.number_format($new_saldo,2).' Correctamente!!');
		}else {
			return redirect(env('admin').'/user')->with('error','Algo ha ocurrido, por favor intenta nuevamente.');
		}
	}	

	public function viewmap($id)
	{
		
		return View(
			$this->folder.'google',
			[
				'data' => User::find($id),
				'form_url'  => env('admin').'/saveMap/'.$id,
				'ApiKey'     => Admin::find(1)->ApiKey_google,
			]
		);
	}

	public function saveMap(Request $Request,$id)
	{
		$data = new User;

		$req = $data->updateMap($Request->All(),$id);
		if ($req == true) {
			return redirect(env('admin').'/user/'.$id.'/edit')->with('message',"Se ha actualizado la ubicación.");
		}else {
			return redirect(env('admin').'/user/'.$id.'/edit')->with('error','Algo ha ocurrido, por favor intenta nuevamente.');
		}
	}
}