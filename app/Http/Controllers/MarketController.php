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
        DB::table('market')->delete();
        $groups = Group::all()
        ->map(function ($group) use (& $response) {

            $salesByGroup = DB::table('sales')->where('id_group', '=', $group->id)->get();
            $karatekas = Karateka::all();
          

            foreach ($salesByGroup  as $value) {
              $karatekas = $karatekas->where('id', '<>', $value->id_karatekas); // Get all the karateka that don't have that id 
            }

            $numberKaratekasInMarket = 10;
            $karatekaRandom = $karatekas->random($numberKaratekasInMarket);  
    
    
          
            if( count($karatekas) <= $numberKaratekasInMarket){
                $re = "All karatekas are in group";
                var_dump($re);
              
              $response = array('code' => 200, 'msg' => 'All karatekas are in group',);

            }else{
              //  DB::table('market')->delete();
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



    public function showMarketByGroup($id_group){
 
        $response = array('code' => 400, 'error_msg' => []);
        try {
            $market = Market::where('id_group','=',$id_group)->get();
            
            $karatekas = Karateka::all();
            $karatekasByMarket = array();

            foreach ($market as $onSale) {
                foreach ($karatekas as $karateka){

                  if($karateka->id == $onSale->id_karatekas) {

                    $karatekasByMarket[] = $karateka;
                  }

                }
            }
            $response = array('code' => 200, 'karatekas' => $karatekasByMarket, 'msg' => 'Get all groups by participant');
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);
    }


}
