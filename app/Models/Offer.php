<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    protected $fillable=[
        'offer_name',
        'offer_start_time',
        'offer_end_time',
        'offer_price',
        'offer_descripe',
        'professor_id',

    ];

    function professor(){
        return $this->belongsTo(Professor::class);
    }


    function Bookets(){
        return $this->hasMany(Booket::class);
    }
}
