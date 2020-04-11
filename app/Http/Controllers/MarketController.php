<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Karateka;
use App\Sale;
use App\Group;
use App\Market;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{

    public function updateMarket()
    {  
        $response = array('code' => 400, 'error_msg' => []);
        $groups = Group::all()
        ->map(function ($group) use (& $response) {

            $salesByGroup = DB::table('sales')->where('id_group', '=', $group->id)->get();
            $karatekas = Karateka::all();
          

            foreach ($salesByGroup  as $value) {
              $karatekas = $karatekas->where('id', '<>', $value->id_karatekas); // Get all the karateka that don't have that id 
            }

            $numberKaratekasInMarket = 5;
            $karatekaRandom = $karatekas->random($numberKaratekasInMarket);  
    
    
          
            if( count($karatekas) <= $numberKaratekasInMarket){
                $re = "All karatekas are in group";
                var_dump($re);
              
              $response = array('code' => 200, 'msg' => 'All karatekas are in group',);

            }else{
                foreach($karatekaRandom as $karateka)  {
    
                    $onSalePlayer = new Market();
                  
                    $onSalePlayer->id_group = $group->id;
                  
                    $onSalePlayer->id_karatekas = $karateka->id;
                    $onSalePlayer->date_release = $karateka->created_at;
                    $onSalePlayer->save();      
                }  
                
               $response = array('code' => 200, 'group' => $onSalePlayer, 'msg' => 'Karateka on sale created');
            } 

  
        });
        return response($response, $response['code']);
    }

    public function showMarketByGroup($idGroup)
    {
        $response = array('code' => 400, 'error_msg' => []);
        try {
            $karateka = Karateka::find($idGroup);
            if (!empty($karateka)) {
                $response = ['karatekas' => $karateka->id, 'groups' => []];
                $karatekasInMarket = $karateka->karatekasByGroupInMarket;
                return   $response = array('code' => 200, 'Karatekas by group in market' => $karatekasInMarket, 'msg' => 'Get all karatekas by group in market');
            } else {
                return $response = array('code' => 401, 'error_msg' => 'Unautorized');
            }
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);

    }

}
