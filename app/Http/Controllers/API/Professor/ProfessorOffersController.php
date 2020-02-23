<?php

namespace App\Http\Controllers\API\Professor;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Professor;
use Illuminate\Http\Request;

class ProfessorOffersController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Professor $professor)
    {
        $offers=$professor->offers();

        return $this->showAll($offers);
    }
}
