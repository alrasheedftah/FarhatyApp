<?php

namespace App\Http\Controllers\API\Offer;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Professor;
use Illuminate\Http\Request;

class OfferController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers=Offer::all();

        return $this->showAll($offers);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        return $this->showAll($offer);
    }


    public function store(Request $request)
    {
        $role=[
            'offer_name'=>'required|max:50',
            'offer_start_time'=>'required',
            'offer_end_time'=>'required',
            'offer_price'=>'required',
            'offer_descripe'=>'required',
        ];

        $this->validate($request,$role);

        $offer=Offer::create($request->all());

        return $this->showOne($offer);
    }

    public function update(Request $request, Offer $offer)
    {
        $role=[
            'offer_name'=>'required|max:50',
            'offer_start_time'=>'required',
            'offer_end_time'=>'required',
            'offer_price'=>'required',
            'offer_descripe'=>'required',
        ];

        $this->validate($request,$role);
        $offer->update($request->all());

        return $this->showOne($offer);
    }


    public function destroy(Offer $offer){
        $offer->delete();

        return $this->showOne($offer);
    }

    }
