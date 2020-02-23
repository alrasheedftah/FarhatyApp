<?php

namespace App\Http\Controllers\API\Specialty;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyProfessorsController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Specialty $specialty)
    {
        $professors=$specialty->professors();
        return  $this->showAll($professors);
    }
}
