<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Order;
use App\Admin;
use App\Delivery;
use App\Exports\ServiceExport; 
use App\Exports\StaffExport;
use DB;
use Validator;
use Redirect;
use IMS;
use Excel;
class ReportController extends Controller {

	public $folder  = "admin/report.";
	/*
	|---------------------------------------
	|@Showing all records
	|---------------------------------------
	*/
	public function index()
	{
		$admin = new Admin;
		$clients = new Admin;

		if ($admin->hasperm('Reportes de ventas')) {
			return View($this->folder.'index',[
				'form_url' => env('admin').'/exportData',
				'admin' => $admin,
				'clients' => Admin::where('id','!=',1)->get()
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Reportes de ventas');
		}
	} 

	public function index_staff()
	{   $admin = new Admin;
		$res = new Delivery;

		return View($this->folder.'index_staff',[
			'data' => $res->getAll(0),
			'form_url' => env('admin').'/exportData_staff',
            'admin' => $admin
		]);
	}

	public function report(Request $Request)
	{
		$admin = new Admin;

		if ($admin->hasperm('Reportes de ventas')) {

		$res = new Order;
		return View($this->folder.'payment',[
			'data' => $res->getReport($Request->all()),
			'from' => $Request->get('from') ? date('d-M-Y',strtotime($Request->get('from'))) : null,
			'to'   => $Request->get('to') ? date('d-M-Y',strtotime($Request->get('to'))) : null,
		]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Reportes de ventas');
		}

	}

	public function payment()
	{
		$admin = new Admin;

		if ($admin->hasperm('Reportes de ventas')) {

		$res = new User;

		return View($this->folder.'payment',['data' => $res->getAll(),'form_url' => env('admin').'/paymentReport']);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Reportes de ventas');
		}
	}

	public function paymentReport()
	{
		return Excel::download(new OrderExport, 'payment.xlsx');
	}

	public function exportData(Request $Request)
	{
		if ($Request->get('type_report') == 'excel') {
			return Excel::download(new ServiceExport, 'report.xlsx');
		}elseif($Request->get('type_report') == 'csv') {
			return Excel::download(new ServiceExport, 'reporte_ventas.csv');
		}elseif($Request->get('type_report') == 'pdf') {
			return Excel::download(new ServiceExport, 'reporte_ventas.pdf');
		}
    }

	public function exportData_staff(Request $Request)
	{
		if ($Request->get('type_report') == 'excel') {
			return Excel::download(new StaffExport, 'socios_repartidores_report.xlsx');
		}elseif($Request->get('type_report') == 'csv') {
			return Excel::download(new StaffExport, 'socios_repartidores_report.csv');
		}elseif($Request->get('type_report') == 'pdf') {
			return Excel::download(new StaffExport, 'socios_repartidores_report.pdf');
		}

	}
}
