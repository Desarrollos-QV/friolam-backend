<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Admin;
use App\User;
use App\AppUser;
use App\Delivery;
use DB;
use Validator;
use Redirect;
class AdminController extends Controller {

	public $folder = "admin.";

	/*
	|------------------------------------------------------------------
	|Index page for login
	|------------------------------------------------------------------
	*/
	public function index()
	{
		return View($this->folder.'index',[
			'form_url' => Asset(env('admin').'/login')
		]);
	}

	/*
	|------------------------------------------------------------------
	|Login attempt,check username & password
	|------------------------------------------------------------------
	*/
	public function login(Request $request)
	{
		$username = $request->input('username');
		$password = $request->input('password');

		if (auth()->guard('admin')->attempt(['username' => $username, 'password' => $password]))
		{
			return Redirect::to(env('admin').'/home')->with('message', 'Welcome ! Your are logged in now.');
		}
		else
		{
			return Redirect::to(env('admin').'/login')->with('error', 'Username password not match')->withInput();
		}
	}

	/*
	|------------------------------------------------------------------
	|Homepage, Dashboard
	|------------------------------------------------------------------
	*/
	public function home()
	{
		$admin = new Admin;
		
		return View($this->folder.'dashboard.home',[
			'overview'	=> $admin->overview(),
			'ApiKey'     => Admin::find(1)->ApiKey_google,
			'admin'		=> $admin,
			'schart'	=> $admin->storeChart(),
			'dboysRate' => $admin->RankingDboy(),
			'boys'		=> Delivery::where('status',0)->get(),
			'currency'  => Admin::find(1)->currency
		]);
	}

	/*
	|------------------------------------------------------------------
	|Logout
	|------------------------------------------------------------------
	*/
	public function logout()
	{
		auth()->guard('admin')->logout();

		return Redirect::to(env('admin').'/login')->with('message', 'Logout Successfully !');
	}

	/*
	|------------------------------------------------------------------
	|Account setting's page
	|------------------------------------------------------------------
	*/
	public function setting()
	{
		$admin = new Admin;

		if($admin->hasperm('Dashboard - Configuraciones')){
			return View($this->folder.'dashboard.setting',[
				'data' 		=> auth()->guard('admin')->user(),
				'form_url'	=> Asset(env('admin').'/setting'),
				'admin'		=> new Admin,

				]);
		}else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección configuraciones');
		}
	}

	/*
	|------------------------------------------------------------------
	|update account setting's
	|------------------------------------------------------------------
	*/
	public function update(Request $Request)
	{
		$admin = new Admin;

		if($admin->matchPassword($Request->get('password')))
		{
			return Redirect::back()->with('error','Opps! Your current password is not match.');
		}
		else
		{
			$admin->updateData($Request->all());

			return Redirect::back()->with('message','Account Information Updated Successfully.');
		}
	}

	public function admin()
	{
		return Redirect(env('admin'));
	}


}
