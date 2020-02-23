<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');

    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('isAdmin');
       if(Gate::allows('isAdmin')||Gate::allows('isAuther'))
        return  User::latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=> 'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users',
            'password'=>'required|string|min:6',
            'type'=>'required',
        ]);
        return User::create([
           'name'=>$request->name,
           'email'=>$request->email,
           'password'=>Hash::make($request->password),
           'bio'=>$request->bio,
           'type'=>$request->type,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function updateProfile(Request $request)
    {
        $user=auth('api')->user();

        $this->validate($request,[
            'name'=> 'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,

        ]);
        $currentPhoto=$user->photo;
        if($request->photo != $currentPhoto){
            $name=time().'.'.explode('/',explode(':',substr($request->photo,0,strpos($request->photo,';')))[1])[1];
            Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo'=>$name]);

            $oldProfile=public_path('img/profile/').$currentPhoto;
            if(file_exists($oldProfile))
            @unlink($oldProfile);
        }

        $user->update($request->all());
        return ['message'=>'Updated'];
    }

    public function profile()
    {
        return auth('api')->user();
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request,[
            'name'=> 'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
            'password'=>'required|string|min:6',
            'type'=>'required',
        ]);
        $request->merge(['password'=>Hash::make($request->password)]);
        $user->update($request->all());
        return ['message'=>'User Updated Successfully '];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $user->delete();
        return ['message'=>'User Deleted '];
    }



    function search(){

        if($search=\Request::get('q')){
            $users=User::where(function ($query) use ($search){
               $query->where('name','LIKE',"%$search%")->orWhere('email','LIKE',"%$search%");
            })->paginate(10);
        }
        else{
            $users=User::latest()->paginate(5);
        }
        return $users;
    }

}
