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
Route::delete('/group/delete/{id}', 'GroupController@deleteGroup' );
Route::get('/group/getall', 'GroupController@getAllGroup' );

//PARTICIPANTS
Route::post('/participant', 'ParticipantController@addParticipant' );
Route::get('/participant/getall/{id}', 'ParticipantController@getAllParticipantsByGroup' );
Route::delete('/participant/delete/{id}', 'ParticipantController@deleteParticipant' );
Route::get('/participant/add/karatekas', 'ParticipantController@randomKaratekasByPlayer' );
//Karatekas
Route::post('/karatekas', 'KaratekasController@createKarateka' );
Route::delete('/karatekas/delete/{id}', 'KaratekasController@deleteKarateka' );
Route::put('/karatekas/update/{id}', 'KaratekasController@updateKarateka' );

//Championship
Route::post('/championship', 'ChampionshipController@createChampionship' );

//Result Karatekas
Route::post('/result/karateka', 'ResultKaratekaController@addResultKarateka' );
Route::get('/result/karateka/{id}', 'ResultKaratekaController@getAllResultByKarateka' );

//Market

Route::post('/market/update', 'MarketController@updateMarket' );
Route::get('/market/show/karatekas/{id}', 'MarketController@showMarketByGroup' );

//Bids

Route::post('/bid/{id}', 'BidController@createBid' );