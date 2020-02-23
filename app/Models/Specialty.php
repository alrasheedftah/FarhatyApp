<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{

    protected $fillable=[
      'special_name',
        'special_description',
        'proffesors_total',
        'specialty_icon'
    ];

    function professors(){
        return $this->hasMany(Professor::class);
    }



}
