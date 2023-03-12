<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
class PlaceLoading extends Authenticatable
{
    protected $table = "places_loading";

    protected $fillable = [
        'type_place',
        'download_cost',
        'name',
        'address',
        'lat',
        'lng',
        'phone',
        'person_contact',
        'email_contact',
        'type',
        'observations',
        'status'
    ];

    public function getAll()
    {
        return PlaceLoading::orderBy('id','DESC')->get();
    }
}