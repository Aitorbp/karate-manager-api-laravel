<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Market;
use App\Bid;
use App\Karateka;
use App\Participant;
use Illuminate\Support\Str;
class BidController extends Controller
{

    public function createBid(Request $request){

        $response = array('code' => 400, 'error_msg' => []);

        if (!$request->id_karatekas) array_push($response['error_msg'], 'id_karateka is required');
        if (!$request->id_group) array_push($response['error_msg'], 'idGroup is required');
        if (!$request->id_participant) array_push($response['error_msg'], 'id_participant is required');
        if (!$request->bid) array_push($response['error_msg'], 'bid is required');
        if (!count($response['error_msg']) > 0) {
        try {
        
            $karatekaMarket = Market::where("id_karatekas","=",$request->id_karatekas)->where("id_group","=",$request->id_group)->first();
            $karatekas = Karateka::all();

           // var_dump($karatekaMarket->id_karatekas);
          
             $bid = new Bid();
            $this->restrictionMinimunBid($request, $karatekas, $bid,  $response, $karatekaMarket);
          
           // var_dump($market);
        }catch(\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
           
    }
            
   return response($response, $response['code']);

}

public function restrictionMinimunBid(Request  $request, $karatekas,  $bid,  & $response, & $karatekaMarket)
{

    return Karateka::all()
    ->map(function ($allKaratekas) use ($karatekas, $request, & $bid, & $response, & $karatekaMarket){
            if($karatekaMarket->id_karatekas == $allKaratekas->id ){
               if($request->bid > $allKaratekas->value){
              //     var_dump($allKaratekas->value);
             //      var_dump($request->bid);
               
                $bidFilter =$request->bid;

                $flight = Bid::updateOrCreate(
                    [
                    'id_participants' => $request->id_participant,
                    'id_karatekas' => $karatekaMarket->id_karatekas,
                    'id_group' => $karatekaMarket->id_group
                    ],

                    ['id_market' => $karatekaMarket->id, 
                    'id_group' => $karatekaMarket->id_group,
                    'id_karatekas' => $karatekaMarket->id_karatekas,
                    'id_participants' => $request->id_participant,
                    'bid' => $bidFilter
                    ]);
                    $response = array('code' => 200, 'bid' => $flight, 'msg' => 'Bid created'); 
             //   var_dump($bidFilter);
                
                $msg ="The bid is more than the value of karateka /  Bid created.";
              //  var_dump($msg);
               }
               else{
                $response = array('code' => 200,  'msg' => '"The bid is less than the value of karateka'); 
                   $error ="The bid is less than the value of karateka";
                   var_dump($error);
               }
            }
    });

}
    

}
