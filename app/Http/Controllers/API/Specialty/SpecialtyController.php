<?php

namespace App\Http\Controllers\API\Specialty;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SpecialtyController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $specialties=Specialty::all();//latest()->paginate(5);
        return $this->showAll($specialties);
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
            'special_name'  =>'required|string|max:191',
            'special_description'=>'required||string|max:255',
//            'specialty_icon'=>'required|image|mimes:png'
        ];

        $this->validate($request,$role);
        $name=time().'.'.explode('/',explode(':',substr($request->specialty_icon,0,strpos($request->specialty_icon,';')))[1])[1];
        Image::make($request->specialty_icon)->save(public_path('icons/specialties/').$name);
        $request->merge(['specialty_icon'=>$name]);
        //$request->proffesors_total=0;
        $data=$request->all();
        $data['proffesors_total']=0;

//        $path = $request->file('specialty_icon')->storeAs('specialties',time().'.'.$request->specialty_icon->getClientOriginalExtension(),  'images');
//        $data=$request->all();
//        $data['specialty_icon']=$path;
        $specialty=Specialty::create($data);
        return $this->showOne($specialty,200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Specialty $specialty)
    {
        return  $this->showOne($specialty);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specialty $specialty)
    {

        $role=[
            'special_name'  =>'required|string|max:191',
            'special_description'=>'required||string|max:255',
            'specialty_icon'=>'required|image'
        ];

        $currentPhoto=$specialty->specialty_icon;
        $oldIcon=public_path('icons/specialties/').$currentPhoto;
        if($request->specialty_icon == $currentPhoto){
            if(file_exists($oldIcon))
            @unlink($oldIcon);
        }
        $name=time().'.'.explode('/',explode(':',substr($request->specialty_icon,0,strpos($request->specialty_icon,';')))[1])[1];
        Image::make($request->specialty_icon)->save(public_path('icons/specialties/').$name);
        $request->merge(['specialty_icon'=>$name]);


        $specialty->update($request->all());

        return $this->showOne($specialty);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specialty $specialty)
    {
        $specialty->delete();
        $oldIcon=public_path('').$specialty->specialty_icon;
        if(file_exists($oldIcon))
            @unlink($oldIcon);
        return $this->showOne($specialty);
    }
}
