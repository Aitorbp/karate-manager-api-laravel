<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Participant;
use App\Sale;
use App\Karateka;
use App\Market;
use Illuminate\Support\Facades\DB;
class GroupController extends Controller
{
    public function getGroupByParticipant($id)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (!$id) array_push($response['error_msg'], 'id is required');

        $group = Group::find($id);
        if (!empty($group)){
            try {
        
                $group->get();
                //  var_dump($group);
                //  die;
                $response = array('code' => 200, 'group' => $group, 
                                'msg' => 'Group, admin participant and karatekas ramdon created');
            
            } catch (\Throwable $th) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }else{
             $response = array('code' => 400, 'error_msg' => "Group doesn't exist"); 
        }
           
        return response($response,$response['code']);

    }


 
    public function newGroup(Request $request)
    {
       
        $response = array('code' => 400, 'error_msg' => []);
     
       
        if (isset($request)) {
     
            if (!$request->api_token) array_push($response['error_msg'], 'Token is required');
            if (!$request->name_group) array_push($response['error_msg'], 'Name is required');
            if (!$request->budget) array_push($response['error_msg'], 'Budget is required');
            if (!$request->gender) array_push($response['error_msg'], 'Gender is required');
            if (!$request->id_user) array_push($response['error_msg'], 'Id_group name is required');
            if (!$request->password_group) array_push($response['error_msg'], 'Id_group name is required');
         

            if (!count($response['error_msg']) > 0) {
                
                //TODO - TO TEST
    
                    if (!count($response['error_msg']) > 0) {
                        try {
                            $group = new Group();
                            $group->budget = $request->budget;
                            $group->gender = $request->gender;
                            $group->id_user = $request->id_user; 
                            $group->password_group = hash('sha256', $request->password_group);
                            $group->name_group = $request->name_group;
                            $group->save();


                            $adminParcitipant = new Participant();
                            $adminParcitipant->own_budget = $request->budget;
                            $adminParcitipant->id_user = $request->id_user;
                            $adminParcitipant->points = 0;
                            $adminParcitipant->admin_user_group = 1;

                            $adminParcitipant->id_group = $group->id;
                            $adminParcitipant->save();

                            $karatekas = Karateka::all();
                            $karatekaRandom = $karatekas->random(2);
                            foreach ($karatekaRandom as $key) {
                                $sale = new Sale();
                                $sale->id_group = $group->id;
                                $sale->id_participants =  $adminParcitipant->id;
                                $sale->id_karatekas = $key->id;
                                $sale->bid_participant = $key->value;
                                $sale->save();
                            }
                            self::createMarketByGroup($group->id);

                            $response = array('code' => 200, 'group' => $group, 
                            'AdminParticipant' => $adminParcitipant, 
                            'Karatekas' => $karatekaRandom, 
                            'msg' => 'Group, admin participant and karatekas ramdon created');
                        
                        
                        } catch (\Exception $exception) {
                            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                        }

                    } else {
                        $response = array('code' => 400, 'error_msg' => "Group already registered");
                    }


            } else {
                $response['error_msg'] = 'Please introduce the required data';
            }

        } else {
            $response['error_msg'] = 'Nothing to create';
        }

        return response($response,$response['code']);
    }

    public function createMarketByGroup($id_group){
        $karatekas = Karateka::all();
        $numberKaratekasInMarket = 3;
        $karatekaRandom = $karatekas->random($numberKaratekasInMarket);  
        if( count($karatekas) <= $numberKaratekasInMarket){
            $re = "All karatekas are in group";
            var_dump($re);
          
          $response = array('code' => 200, 'msg' => 'All karatekas are in group',);

        }else{
            foreach($karatekaRandom as $karateka)  {

                $onSalePlayer = new Market();
              
                $onSalePlayer->id_group = $id_group;
              
                $onSalePlayer->id_karatekas = $karateka->id;
                $onSalePlayer->date_release = $karateka->created_at;
                $onSalePlayer->save();      
            }  
            
           $response = array('code' => 200, 'group' => $onSalePlayer, 'msg' => 'Karateka on sale created');
        } 
    return response($response, $response['code']);
}

//No funciona con el admin_user_group
    public function deleteGroup($id, Request $request){
        if (isset($id)) {
            //TODO - TO TEST
            try {
                $group = Group::find($id);
                
                var_dump($request->admin_user_group);

                if (!empty($group) && $request->admin_user_group == 1) {
                    try {
                        $group->delete();
                        $response = array('code' => 200, 'msg' => 'group deleted');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
        
                } else {
                    $response = array('code' => 401, 'error_msg' => 'Unautorized, only admin group can delete this group');
                }

            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
       
       return response($response,$response['code']);
    }

    public function getAllGroup()
    {
        $response = array('code' => 400, 'error_msg' => []);

        try {
            $group = Group::all();
            if (count($group) > 0) {
                $response = array('code' => 200, 'groups' => $group);
            } else {
                $response = array('code' => 404, 'error_msg' => ['group not found']);
            }
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }

        return response($response, $response['code']);
    }
}
