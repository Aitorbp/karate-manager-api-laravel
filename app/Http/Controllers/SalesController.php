<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Market;
use App\Bid;
use App\Karateka;
use App\Sale;
use App\Participant;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{

    //Función para asignar el karateka a la puja máxima que haya hecho un participante
    public function soldKarateka()
    {
        $response = array('code' => 400, 'error_msg' => []);

        $bidsPerKaratekas = DB::table('bids')
        ->select([DB::raw('MAX(bids.bid) AS max_bid'), 'bids.id_group', 'bids.id_karatekas', 'bids.id_participants'])
        ->groupBy('bids.id_group', 'bids.id_karatekas')
        ->get();
        foreach($bidsPerKaratekas as $best_bid){
            $sold = new Sale();       
            $sold->bid_participant = $best_bid->max_bid;
            $sold->id_group = $best_bid->id_group;
            $sold->id_participants = $best_bid->id_participants;
            $sold->id_karatekas = $best_bid->id_karatekas;
            $sold->starting = 0;
            $sold->save();
        };
        DB::table('bids')->delete();
        $response = array('code' => 200, 'Karatekas order by group' => $bidsPerKaratekas, "Sold karateka" => $bidsPerKaratekas);

       return response($response, $response['code']);
    }

//Mostrar todos los karatekas vendidos por un karateka
    public function showSoldByParticipant($idParticipant)
    {
        $response = array('code' => 400, 'error_msg' => []);
        try {

            $sales = Sale::where('id_participants','=',$idParticipant)->get();
            
            if (!empty($sales)) {
                // var_dump($sales);
                //         die;
                $karatekas = Karateka::all();
                $karatekasOfParticipant = array();

                foreach ($sales as $sale) {
                    foreach ($karatekas as $karateka){

                      if( $karateka->id == $sale->id_karatekas) {
    
                        $karatekasOfParticipant[] = $karateka;
                        
                      }
                
                    }
                }
            $response = array('code' => 200, 'karatekas' => $karatekasOfParticipant, 'msg' => 'karatekas bougth by participant');
            } else {
                return $response = array('code' => 401, 'error_msg' => 'Unautorized');
            }
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);

    }

    //Resta de dinero cuando compras a los karatekas
    public function makePaymentsParticipants()
    {
     
        $response = array('code' => 400, 'error_msg' => []);
        $sales = Sale::all()
        ->map(function ($sale) use (& $response){
            $participants = Participant::all()
            ->map(function ($participant) use (& $response,  $sale ){
                if($sale->id_participants == $participant->id){
                    $participant->own_budget = $participant->own_budget - $sale->bid_participant;
                    $participant->save();
                    $response = array('code' => 200, 'Participants payments done');
                }
            });
        });

        return response($response, $response['code']);
    }
//calcular la media de un jugador en función del precio al que se ha vendido en el mercado
    public function averageKaratekas()
    {
        $response = array('code' => 400, 'error_msg' => []);
        
       
        $avgPerKaratekas = DB::table('sales')
        ->select([DB::raw('AVG(sales.bid_participant) AS avg_bid'),  'sales.id_karatekas'])
        ->groupBy('sales.id_karatekas')
        ->get()
        ->map(function ($avg) use (& $response){
            $karateka = Karateka::all()
            ->map(function ($karatekaFilter) use (& $response, $avg){

                if($avg->id_karatekas == $karatekaFilter->id){
                    $karatekaFilter->value = $avg->avg_bid;
       
                    $karatekaFilter->save();
                    $response = array('code' => 200, 'Karatekas value updated');
                }
            });
           
        });
    

      
        return response($response, $response['code']);
    }

    //Vender tu propio karateka al mercado
    public function sellOwnKaratekaByParticipant($idParticipant, $idGroup, $id_karatekas){

        // var_dump($idParticipant);
        //     die;
        $response = array('code' => 400, 'error_msg' => []);
        try {

         
            $sales =  Sale::where('id_group','=',$idGroup)->where('id_participants','=',$idParticipant)->where('id_karatekas','=', $id_karatekas)->first();
            $participant = Participant::where('id', "=",$idParticipant )->first();
         
            $bidParticipant= $sales->bid_participant;
           
            $participant->own_budget =  $participant->own_budget + $bidParticipant;
            $participant->save();
            $sales->delete();
        
            $response = array('code' => 200, 'participant' => $participant, 'msg' => 'Get all groups by participant');
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);

    }

// Coger todos los karatekas tutilares
    public function getStartingKaratekaByParticipant(  $id_participants){
        $response = array('code' => 400, 'error_msg' => []);
        $starting = 1;
        // var_dump($request->id_participants);
        try {
           
            $sales = Sale::where('id_participants','=',$id_participants)->where('starting','=', $starting)->get();
       
            // var_dump($sales );
            // die;
            $karatekas = Karateka::all();
            $startingKaratekas = array();
            foreach ($sales as $sale) {
                foreach ($karatekas as $karateka){

                  if($sale->id_karatekas == $karateka->id) {

                    $startingKaratekas[] = $karateka;
               
                  }
            
                }
            }
            $response = array('code' => 200, 'karatekas' => $startingKaratekas, 'msg' => 'Get all karatekas starting by participant');
           
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);
    }

    //Coger karatekas que están en banquillo
    public function getAlternateKaratekaByParticipant(  $id_participants){
        $response = array('code' => 400, 'error_msg' => []);
        $starting = 0;
        // var_dump($request->id_participants);
        try {
           
            $sales = Sale::where('id_participants','=',$id_participants)->where('starting','=', $starting)->get();
       
            // var_dump($sales );
            // die;
            $karatekas = Karateka::all();
            $startingKaratekas = array();
            foreach ($sales as $sale) {
                foreach ($karatekas as $karateka){

                  if($sale->id_karatekas == $karateka->id) {

                    $startingKaratekas[] = $karateka;
               
                  }
            
                }
            }
            $response = array('code' => 200, 'karatekas' => $startingKaratekas, 'msg' => 'Get all karatekas starting by participant');
           
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);
    }

    //Poner de titular a un karateka
    public function postStartingKarateka(Request $request){
        $response = array('code' => 400, 'error_msg' => []);
        $starting = 1;
        if (isset($request->id_participants)){
            //TODO - TO TEST
            try {
                $sale = Sale::where('id_participants','=',$request->id_participants)->where('id_karatekas','=', $request->id_karatekas)->first();

                if (!empty($sale)) {
                    try {
                        $sale->starting = $starting ? $starting : $sale->starting;
                       
                        $sale->save();
                        $response = array('code' => 200, 'msg' => 'Sale starting updated');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
                }
            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }

        } else {
            $response['error_msg'] = 'Nothing to update';
        }

       return response($response,$response['code']);

    }

//Poner de suplente a un karateka
    public function postAlternateKarateka(Request $request){
        $response = array('code' => 400, 'error_msg' => []);
        $alternate = 0;
        if (isset($request->id_participants)){
            //TODO - TO TEST
            try {
                $sale = Sale::where('id_participants','=',$request->id_participants)->where('id_karatekas','=', $request->id_karatekas)->first();

                if (!empty($sale)) {
                    try {
                        $sale->starting = $alternate;
                       
                        $sale->save();
                        $response = array('code' => 200, 'msg' => 'Sale alternate updated');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
                }
            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }

        } else {
            $response['error_msg'] = 'Nothing to update';
        }

       return response($response,$response['code']);

    }


}
