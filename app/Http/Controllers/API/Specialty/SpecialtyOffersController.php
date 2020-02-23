<?php

namespace App\Http\Controllers\API\Specialty;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyOffersController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Specialty $specialty)
    {
        $offers=$specialty->professors()->with('offers')
        ->get()
        ->pluck('offers')
        ->unique();

        return $this->showAll($offers);
    }




}
