<?php

namespace App\Http\Controllers\API\Professor;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Professor;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class ProfessorController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professors=Professor::all();
        return $this->showAll($professors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role=[
            'first_name'=>'required|max:50',
            'last_name'=>'required|max:50',
            'address'=>'required|max:50',
            'phone'=>'required|max:10|unique:professors,phone',
            'description'=>'required',

            'gender'=>'required'

        ];

        $this->validate($request,$role);

        $name=time().'.'.explode('/',explode(':',substr($request->image,0,strpos($request->image,';')))[1])[1];
        Image::make($request->image)->save(public_path('img/profile/professors/').$name);
        $request->merge(['image'=>$name]);

        $professor=Professor::create($request->all());

        return $this->showOne($professor);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Professor $professor)
    {
        return  $this->showOne($professor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Professor $professor)
    {
        $role=[
            'first_name'=>'required|max:50',
            'last_name'=>'required|max:50',
            'address'=>'required|max:50',
            'phone'=>'required|max:10|unique:professors,phone_prof',
            'description'=>'required',
            'gender'=>'required'

        ];

        $this->validate($role);
        $professor->update($request->all());

        return $this->showOne($professor);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Professor $professor)
    {
        $professor->delete();
        $profileImage=public_path('img/profile/professor').$professor->image;
        if(file_exists($profileImage))
            @unlink($profileImage);
        return $this->showOne($professor);
    }
}
