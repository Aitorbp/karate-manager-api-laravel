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

Route::middleware ('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//USER SERVICE
Route::post('/user', 'UserController@registerUser' );
Route::post('/user/generateToken/{id}', 'UserController@generateToken' );
Route::post('/user/login', 'UserController@loginUser' );
Route::get('/user/one', 'UserController@getUser' );
Route::post('/user/update', 'UserController@updateUser' );
Route::delete('/user/delete', 'UserController@deleteUser' );

//IMAGES SERVICE
Route::post('/upload/image', 'ImagesController@uploadImageGroup' );
Route::post('/upload/image/karateka', 'ImagesController@uploadImageKarateka' );
Route::post('/upload/image/user', 'ImagesController@uploadImageUser' );
//GROUP SERVICES

Route::middleware ('auth:api')->post('/group', 'GroupController@newGroup' );
Route::delete('/group/delete/{id}', 'GroupController@deleteGroup' );
Route::get('/group/getall', 'GroupController@getAllGroup' );
Route::get('/group/one/{id}', 'GroupController@getGroupByParticipant' );
//PARTICIPANTS
Route::middleware ('auth:api')->post('/participant', 'ParticipantController@addParticipant' );
Route::get('/participant/getall/{id}', 'ParticipantController@getAllParticipantsByGroup' );
Route::delete('/participant/delete/{id}', 'ParticipantController@deleteParticipant' );
Route::get('/participant/add/karatekas', 'ParticipantController@randomKaratekasByPlayer' );

Route::get('/participant/get/groupsByParticipant/{id_user}','ParticipantController@getAllGroupByParticipant');

Route::get('/participant/get/one/{id_user}/{id_group}','ParticipantController@getParticipantByGroup');
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

Route::post('/bid', 'BidController@createBid' );


//Bids between rivals

Route::post('/bid/rivals/create', 'BidBetweenRivalsController@createBidRival' );

Route::get('/bid/to/rivals/{id_participant_bid_send}', 'BidBetweenRivalsController@myBidsToRivals' );

Route::get('/bid/from/rivals/{id_participant_bid_receive}', 'BidBetweenRivalsController@myBidsRecieveFromToRivals' );

Route::get('/bid/rivals/accepted/{id_participant_bid_send}/{id_participant_bid_receive}/{id_karateka}', 'BidBetweenRivalsController@acceptBet' );

Route::delete('/bid/rivals/refused/ownbid/{id_participant_bid_send}/{id_karateka}', 'BidBetweenRivalsController@refuseOwnBeteeeee' );

Route::delete('/bid/rivals/refused/{id_participant_bid_send}/{id_participant_bid_receive}/{id_karateka}', 'BidBetweenRivalsController@refuseBet' );




//Sales
Route::post('/sold', 'SalesController@soldKarateka' );

Route::get('/karatekas/byparticipant/{id}', 'SalesController@showSoldByParticipant' );

Route::post('/payments', 'SalesController@makePaymentsParticipants' );

Route::post('/average/karatekas', 'SalesController@averageKaratekas' );

Route::delete('/sell/karateka/{id_participants}/{id_group}/{id_karatekas}', 'SalesController@sellOwnKaratekaByParticipant' );

Route::get('/karatekas/starting/{id_participants}', 'SalesController@getStartingKaratekaByParticipant' );

Route::get('/karatekas/alternate/{id_participants}', 'SalesController@getAlternateKaratekaByParticipant' );

Route::post('/karatekas/update/starting', 'SalesController@postStartingKarateka' );

Route::post('/karatekas/update/alternate', 'SalesController@postAlternateKarateka' );