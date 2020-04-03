<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Karateka;
use App\Sale;
use App\Group;
use App\Market;

class MarketController extends Controller
{

    public function updateMarket()
    {

        $response = array('code' => 400, 'error_msg' => []);
        $groups = Group::all()
        
        ->map(function ($group) {
            $karatekaRandom = Karateka::orderBy(\DB::raw('RAND()'))->limit(2)->get();   
            foreach($karatekaRandom as $karateka){

                $onSalePlayer = new Market();
              
                $onSalePlayer->id_group = $group->id;
              
                $onSalePlayer->id_karatekas = $karateka->id;

       
                $onSalePlayer->date_release = $karateka->created_at;
                $onSalePlayer->save();      

              
            }  
            
            $response = array('code' => 200, 'group' => $onSalePlayer, 'msg' => 'Karateka on sale created');
        });
     
    }

}
