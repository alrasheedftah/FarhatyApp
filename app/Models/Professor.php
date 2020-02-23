<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{

    protected $fillable=[
        'first_name','last_name','address','nickname',
        'description','gender','image','beginning_work',
        'working_time','status','phone','specialties_id'
    ];

    function specialty(){
        return $this->belongsTo(Specialty::class);
    }

    function offers(){
        return$this->hasMany(Offer::class);
    }
}
