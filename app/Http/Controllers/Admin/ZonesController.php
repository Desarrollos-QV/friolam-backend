<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\City;
use App\Zones;
use App\Admin;
use DB;
use Validator;
use Redirect;
use IMS;
class ZonesController extends Controller
{
	public $folder  = "admin/zones.";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = new Admin;

		if ($admin->hasperm('Administrar Ciudades')) {

            $res = new City;

            return View($this->folder.'index',[
                'citys'     => $res->getAll(),
                'data'     => Zones::orderBy('id','DESC')->get(),
                'link'      => env('admin').'/zones/',
                'admin'     => $admin,
                'currency'  => Admin::find(1)->currency,
            ]);

		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Ciudades y Zonas');
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $data = $request->all();

        $city = City::find($data['city_id']);
 
        $lims_data['city_id'] = $data['city_id'];
        $lims_data['city_name'] = $city->name;
        $lims_data['name'] = $data['name'];
        $lims_data['coverage'] = $data['coverage'];
        $lims_data['coords'] = $data['coords'];
        $lims_data['status'] = 0;

        // return response()->json(['data' => $data]);
        Zones::create($lims_data);

        return Redirect::to(env('admin').'/zones')->with('message', 'Zonas Agregada con exito...');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = new Admin;
        $citys = new City;

		if ($admin->hasperm('Administrar Ciudades')) {
			return View($this->folder.'add',[
				'data'      => new Zones,
                'citys'     => $citys->getAll(),
				'ApiKey'    => Admin::find(1)->ApiKey_google,
				'form_url'  => env('admin').'/zones',
				'admin'     => $admin,
				'currency'  => Admin::find(1)->currency,
                'type'      => "new"
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Ciudades y Zonas');
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = new Admin;
        $citys = new City;

		if ($admin->hasperm('Administrar Ciudades')) {
			return View($this->folder.'edit',[
				'data'      => Zones::find($id),
                'citys'     => $citys->getAll(),
				'ApiKey'    => Admin::find(1)->ApiKey_google,
				'form_url'  => env('admin').'/zones',
				'admin'     => $admin,
				'currency'  => Admin::find(1)->currency,
                'type'      => "update"
			]);
		} else {
			return Redirect::to(env('admin').'/home')->with('error', 'No tienes permiso de ver la sección Ciudades y Zonas');
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $city = City::find($data['city_id']);
 
        $lims_data['city_id'] = $data['city_id'];
        $lims_data['city_name'] = $city->name;
        $lims_data['name'] = $data['name'];
        $lims_data['coverage'] = $data['coverage'];
        $lims_data['coords'] = $data['coords'];
        $lims_data['status'] = 0;

        $lims_zones_data = Zones::findOrFail($id);
        $lims_zones_data->update($lims_data);

        return Redirect::to(env('admin').'/zones')->with('message', 'Zonas actualizada con exito...');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Zones::findOrFail($id)->delete();
    
        return Redirect::to(env('admin').'/zones')->with('message', 'Zona eliminada con exito...');
    }

    /**
     * Change Status the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $lims_zones_data = Zones::findOrFail($id);

        $lims_zones_data->status = ($lims_zones_data->status == 0) ? 1 : 0;
        $lims_zones_data->save();
        
        return Redirect::to(env('admin').'/zones')->with('message', 'Zona eliminada con exito...');

    }

    /**
     * Obtenemos las coordenadas de la ciudad
     * 
     * 
     */

    public function getCoords($city)
    {
        return City::find($city);
    }
}
