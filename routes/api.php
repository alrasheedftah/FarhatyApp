<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResources([
    'user'=>'API\UserController',
//    'specialties'=>'API\Specialty\SpecialtyController',['except'=>['create','edite']],
//    'specialties.professors'=>'API\Specialty\SpecialtyProfessorsController',['only'=>['index']],
//    'specialties.offers'=>'API\Specialty\SpecialtyOffersController',['only'=>['index']],
]);
//
Route::resource('specialties','API\Specialty\SpecialtyController',['except'=>['create','edite']]);
Route::resource('specialties.professors','API\Specialty\SpecialtyProfessorsController',['only'=>['index']]);
Route::resource('specialties.offers','API\Specialty\SpecialtyOffersController',['only'=>['index']]);
//Route::resource('specialties.transactions','Category\CategoryTransactionController',['only'=>['index']]);
//Route::resource('specialties.buyers','Category\CategoryBuyerController',['only'=>['index']]);



///    Proffesors Resource Route
Route::resource('proffessors','API\Professor\ProfessorController',['except'=>['create','edite']]);
Route::resource('proffessors.specialties','API\Professor\ProfessorSpecialtyController',['only'=>['index']]);
Route::resource('proffessors.offers','API\Specialty\ProfessorOffersController',['only'=>['index']]);






Route::get('profile','API\UserController@profile');

Route::put('profile','API\UserController@updateProfile');


Route::get('findUser','API\UserController@search');


Route::get('invoice',function (){
    return view('invoice');
});
