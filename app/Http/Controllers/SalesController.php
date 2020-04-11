<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Market;
use App\Bid;
use App\Karateka;
use App\Sale;
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


}
