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
            $sold->save();
        };
        
        $response = array('code' => 200, 'Karatekas order by group' => $bidsPerKaratekas, "Sold karateka" => $bidsPerKaratekas);

       return response($response, $response['code']);
    }

    public function showSoldByParticipant($idParticipant)
    {
        $response = array('code' => 400, 'error_msg' => []);
        try {
            $karateka = Karateka::find($idParticipant);
            if (!empty($karateka)) {
                $response = ['karatekas' => $karateka->id, 'participant' => []];
                $karatekasSold = $karateka->karatekasSoldByParticipant;
                return   $response = array('code' => 200, 'Karatekas by group in market' => $karatekasSold, 'msg' => 'Get all karatekas by group in market');
            } else {
                return $response = array('code' => 401, 'error_msg' => 'Unautorized');
            }
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);

    }

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


}
