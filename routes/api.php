<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//USER SERVICE
Route::post('/user', 'UserController@registerUser' );
Route::post('/user/generateToken/{id}', 'UserController@generateToken' );
Route::post('/user/login', 'UserController@loginUser' );
Route::get('/user/one/{id}', 'UserController@getUser' );
Route::post('/user/update', 'UserController@updateUser' );
Route::delete('/user/delete', 'UserController@deleteUser' );


//GROUP SERVICES

Route::post('/group', 'GroupController@newGroup' );


//PARTICIPANTS
Route::post('/participant', 'ParticipantController@addParticipant' );
Route::get('/participant/getall/{id}', 'ParticipantController@getAllParticipantsByGroup' );



Route::post('/karatekas', 'KaratekasController@createKarateka' );