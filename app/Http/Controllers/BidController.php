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
        
        if(isset($request) && isset($idGroup)){
           
            if (!$request->id_karateka) array_push($response['error_msg'], 'id_karateka is required');
          
            try {
                
                $karateka = Karateka::find($idGroup);
                
                if (!empty($karateka)) {
                 
                    $response = ['karatekas' => $karateka->id, 'groups' => []];
                    $karatekasInMarket = $karateka->karatekasByGroupInMarket;

                    $filterKarateka = $karatekasInMarket ->filter(function ($karateka) use ($request) {
                       // Si id es igual al id de la request, cogemos ese karateka.
  
                    //  return $karateka->id == $request->id_karateka;

                            if($karateka->id == $request->id_karateka){
                                $market = Market::all()
                                ->map(function ($market) use ($karateka, $request){
                                    if($market->id_group == $karateka->pivot->id_group && $market->id_karatekas == $karateka->id){
                                       
                                        $participant = Participant::all()
                                        ->map(function ($participant) use ($karateka, $request, $market){

                                            if($participant->id == $request->id_participant && $karateka->pivot->id_group == $participant->id_group){

                                                $bid = new Bid();
                                                $bid->id_market = $market->id;
                                                $bid->id_group = $market->id_group;
                                                $bid->id_karatekas = $market->id_karatekas;
                                                $bid->bid = $request->bid;
                                                $bid->id_participants = $request->id_participant;
                                                $bid->save();
                                           
                                               return $bid;
                                          
                                            }else{
                                                $response = array('code' => 401, 'error_msg' => 'The participant isnt in this group, it is not correct');
                                            }
                                        
                                        });
                                    }
                        
                                });
                              
                            }   
                    });
             var_dump($filterKarateka);
            die;
                    $response = array('code' => 200, 'Bid' => $filterKarateka, 'msg' => 'Bid created');
    
                    
                } else {
                    $response = array('code' => 401, 'error_msg' => 'Unautorized');
                }
            } catch (\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
    
        return response($response, $response['code']);

    }

   
}