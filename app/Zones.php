<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
class Zones extends Authenticatable
{
    protected $table = "zones";

    protected $fillable =[
        "city_id",
        "city_name",
        "name",
        "coverage",
        "coords",
        "status"
    ];

}