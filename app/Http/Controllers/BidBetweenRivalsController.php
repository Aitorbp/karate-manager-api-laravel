<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BidBetweenRivals;
use App\Karateka;
use App\Sale;
use App\Participant;

class BidBetweenRivalsController extends Controller
{
    public function createBidRival(Request $request)
    {

        $response = array('code' => 400, 'error_msg' => []);
        if (!$request->id_karateka) array_push($response['error_msg'], 'id_karateka is required');
        if (!$request->id_participant_bid_send	) array_push($response['error_msg'], 'id_participant_bid_send is required');
        if (!$request->id_participant_bid_receive) array_push($response['error_msg'], '	id_participant_bid_receive is required');
        if (!$request->bid_rival) array_push($response['error_msg'], 'bid rival is required');
        if (!count($response['error_msg']) > 0) {
            try {

                var_dump($request->id_karateka);
                var_dump($request->id_participant_bid_send);
                var_dump($request->id_participant_bid_receive);
                var_dump($request->bid_rival);
               
                $bidRival = new BidBetweenRivals();
              //  var_dump($bidRival);
                $bidRival1 = \App\BidBetweenRivals::updateOrCreate(
                    [
                      
                        'id_participant_bid_send' => $request->id_participant_bid_send,
                        'id_participant_bid_receive' => $request->id_participant_bid_receive,
                        'id_karateka' => $request->id_karateka
                        ],
    
                    [
                        'id_participant_bid_send' => $request->id_participant_bid_send,
                        'id_participant_bid_receive' => $request->id_participant_bid_receive,
                        'id_karateka' => $request->id_karateka,
                        'bid_rival' => $request->bid_rival
                        ]
                        
                );
                var_dump($bidRival);
              //    $bidBetweenRivals->save;
                $response = array('code' => 200, 'bidBetweenRivals' => $bidRival1, 'msg' => 'Bid rival created'); 

            }catch(\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
        return response($response, $response['code']);
    }

// Apuestas que hago yo como usuario a los karatekas de los rivales
public function myBidsToRivals($id_participant_bid_send)
{
    $response = array('code' => 400, 'error_msg' => []);
    if (!$id_participant_bid_send) array_push($response['error_msg'], 'id_participant_bid_send is required');
    if (!count($response['error_msg']) > 0) {
        try {
            $myBidsToRivals = BidBetweenRivals::where('id_participant_bid_send', '=', $id_participant_bid_send)
            ->join('karatekas', 'bid_between_rivals.id_karateka', '=', 'karatekas.id')
            ->join('participants', 'bid_between_rivals.id_participant_bid_receive', '=', 'participants.id')
            ->join('users', 'participants.id_user', '=', 'users.id')
            ->select('karatekas.*', 'bid_between_rivals.bid_rival', 'bid_between_rivals.created_at as date_bid','users.name as name_rival')
            ->get();

        
            $response = array('code' => 200, 'karatekas' => $myBidsToRivals, 'msg' => 'Get all bid karatekas to rivals');


        }catch(\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
    }
    return response($response, $response['code']);
}


// Apuestas que recibo de los demás jugadores a mis karatekas

    public function myBidsRecieveFromToRivals($id_participant_bid_receive)
    {
        $response = array('code' => 400, 'error_msg' => []);
        if (!$id_participant_bid_receive) array_push($response['error_msg'], 'id_participant_bid_receive is required');
        if (!count($response['error_msg']) > 0) {
            try {
                $myBidsFromRivals = BidBetweenRivals::where('id_participant_bid_receive', '=', $id_participant_bid_receive)

            ->join('karatekas', 'bid_between_rivals.id_karateka', '=', 'karatekas.id')
            ->join('participants', 'bid_between_rivals.id_participant_bid_send', '=', 'participants.id')
            ->join('users', 'participants.id_user', '=', 'users.id')
            ->select('karatekas.*', 'bid_between_rivals.bid_rival', 'bid_between_rivals.id_participant_bid_send','bid_between_rivals.id_participant_bid_receive', 'bid_between_rivals.created_at as date_bid','users.name as name_rival')
            ->get();
                $response = array('code' => 200, 'karatekas' => $myBidsFromRivals, 'msg' => 'Get all bid karatekas from rivals');


            }catch(\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
        return response($response, $response['code']);
    }



    public function acceptBet(Request $request)
    {
       
        $response = array('code' => 400, 'error_msg' => []);

        if (!$request->id_participant_bid_send) array_push($response['error_msg'], 'id_participant_bid_send is required');
        if (!$request->id_participant_bid_receive) array_push($response['error_msg'], 'id_participant_bid_receive is required');
        if (!$request->id_karatekas) array_push($response['error_msg'], 'id_participant_bid_receive is required');
 
        if (!count($response['error_msg']) > 0) {
            try {
                // var_dump($request->id_participant_bid_send);
                // var_dump($request->id_participant_bid_receive);
                // var_dump($request->id_karatekas);
                
                //Cambiamos el karateka de participante
                $acceptBidsFromRivals = Sale::where('id_karatekas', '=', $request->id_karatekas)->where('id_participants', '=',$request->id_participant_bid_receive )->first();
              
                $acceptBidsFromRivals->id_participants = $request->id_participant_bid_send;

              

                $acceptBidsFromRivals->save();


              $finishTrade = BidBetweenRivals::where('id_participant_bid_send', '=', $request->id_participant_bid_send)->where('id_karateka', '=', $request->id_karatekas)->where('id_participant_bid_receive', '=', $request->id_participant_bid_receive)->first();


                //Restamos cantidad al participante que compra el karateka
                $participantReceiveMoney = Participant::where('id','=', $request->id_participant_bid_send)->first();
                $participantReceiveMoney->own_budget = $participantReceiveMoney->own_budget - $finishTrade->bid_rival;

               
                 $participantReceiveMoney->save();

               // Sumamos cantidad al participante que vende el karateka
               $participantGiveMoney = Participant::where('id','=', $request->id_participant_bid_receive)->first();
               $participantGiveMoney->own_budget = $participantGiveMoney->own_budget + $finishTrade->bid_rival;

            
               $participantGiveMoney->save();
                //Eliminamos la apuesta de bid_between_rivals para dar por terminado el traspaso
               $finishTrade->delete();

                $response = array('code' => 200,  'msg' => 'Bid accepted');
            }catch(\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }

        return response($response, $response['code']);
    }
}
