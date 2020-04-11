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
    public function createBid(Request $request, $idGroup){

            $response = array('code' => 400, 'error_msg' => []);

            if (!$request->id_karateka) array_push($response['error_msg'], 'id_karateka is required');
            if (!$idGroup) array_push($response['error_msg'], 'idGroup is required');
            if (!$request->bid) array_push($response['error_msg'], 'bid is required');

            try {
                
                $karateka = Karateka::find($idGroup);
                
                if (!empty($karateka)) {
                    
                   // $response = ['karatekas' => $karateka->id, 'groups' => []];
                    $karatekasInMarket = $karateka->karatekasByGroupInMarket;
               
                    $filterKarateka = $karatekasInMarket ->filter(function ($karateka) use ($request, & $response) {
                       // Si id es igual al id de la request, cogemos ese karateka.
                
                   //  return $karateka->id == $request->id_karateka;
                            if($karateka->id == $request->id_karateka){
                         
                                $market = Market::all()
                                ->map(function ($market) use ($karateka, $request, & $response){ // Pasamos el response por referencia con &
                                    if($market->id_group == $karateka->pivot->id_group && $market->id_karatekas == $karateka->id){
                                     
                                        $bid = new Bid();
                                        $participant = Participant::all()
                                      
                                        ->map(function ($participant) use ($karateka, $request, $market, & $response, & $bid){ // Pasamos el response y bid como referencia con &

                                            if($participant->id == $request->id_participant && $karateka->pivot->id_group == $participant->id_group){

                                                $flight = Bid::firstOrCreate(
                                                    ['id_participants' => $request->id_participant, 
                                                    'id_karatekas' => $market->id_karatekas,
                                                    'id_group' => $market->id_group,
                                                    'bid' => $request->bid],

                                                    ['id_market' => $market->id, 
                                                    'id_group' => $market->id_group,
                                                    'id_karatekas' => $market->id_karatekas,
                                                    'id_participants' => $request->id_participant,
                                                    'bid' => 3300
                                                    ]);
                                                    $response = array('code' => 200, 'Bid' => $flight, 'msg' => 'Bid created'); 
                                                  
/*
                                                $bidFilter = Bid::where('id_participants', '=', $request->id_participant)
                                                ->where('id_karatekas', '=',$market->id_karatekas)
                                                ->where('id_group', '=',$market->id_group)
                                                ->where('bid', '=',$request->bid);

                                                if(!$bidFilter->count()){
                                                    $bid->id_market = $market->id;
                                                    $bid->id_group = $market->id_group;
                                                    $bid->id_karatekas = $market->id_karatekas;
                                          
                                                    $bid->id_participants = $request->id_participant;
                                                    self::restrictionMinimunBid($request, $karateka, $bid);
                                                    
                                                    $bid->save();
                                                    
                                                    $response = array('code' => 200, 'Bid' => $bid, 'msg' => 'Bid created'); 
                                                }else{



                                                    $response = array('code' => 400, 'error_msg' => "Bid already registered.");
                                                }
                                             */
                                            }else{
                                                $error ="The participant isnt in this group, it is not correct";
                                                var_dump($error);
                                               // $response = array('code' => 401, 'error_msg' => 'The participant isnt in this group, it is not correct');
                                            }
                                        });
                                    }
                                });
                               
                            }else{
                                $error ="Karateka isnt in this market, it is not correct";
                                var_dump($error);
                               // $response = array('code' => 401, 'error_msg' => 'Karateka isnt in this market, it is not correct');
                            }   
                    });
                } else {
                    $response = array('code' => 401, 'error_msg' => 'Unautorized');
                }
            } catch (\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
       return response($response, $response['code']);

    }
    public function restrictionMinimunBid(Request & $request, $karateka, & $bid)
    {
        $allKaratekas = Karateka::all()
        ->map(function ($allKaratekas) use ($karateka, $request, $bid){
                if($karateka->id == $allKaratekas->id ){
                   if($request->bid > $allKaratekas->value){
                    
                    var_dump($request->bid);
                    return $request->bid;
           
                   
                    $msg ="The bid is more than the value of karateka /  Bid created.";
                    var_dump($msg);
  
                   }
                   else{
                       $error ="The bid is less than the value of karateka";
                       var_dump($error);
                   }
                }
           

        });
  
    }

}
