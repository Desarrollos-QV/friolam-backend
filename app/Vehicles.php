<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
class Vehicles extends Authenticatable
{
    protected $table = "vehiculos";

    protected $fillable =[
        "number_plate",
        "type_driver",
        "capacity",
        "brand",
        "model",
        "color",
        "status"
    ];

    /*
    |--------------------------------------
    |Get all data from db
    |--------------------------------------
    */
    public function getAll($store = 0)
    {
        return Vehicles::where(function($query) use($store) {

        })->orderBy('vehiculos.id','DESC')->get();
    }


}