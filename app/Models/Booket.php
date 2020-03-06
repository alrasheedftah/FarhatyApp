<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booket extends Model
{
    //

    protected $fillable=[
        'booket_place',
        'booket_date',
        'accecpt',
        'offer_id',
    ];
    public function offer(){
        return $this->belongsTo(Offer::class);
    }
}
