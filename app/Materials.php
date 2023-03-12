<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use DB;
class Materials extends Authenticatable
{
    protected $table = "materiales";

    protected $fillable = [
        'nombre',
        'densidad',
        'otros',
        'status'
    ];

    public function getAll()
    {
        return Materials::orderBy('id','DESC')->get();
    }
}