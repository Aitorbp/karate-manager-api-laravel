<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Participant;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    
    public function addParticipant(Request $request)
    {
       
        $response = array('code' => 400, 'error_msg' => []);
        $baseQuery= DB::table("groups");
        try{
            
            if($request->password_group ){
             
                $hashedPassword = hash('sha256', $request->password_group);
                $baseQuery->where('password_group', '=', $hashedPassword);
            }        
             if($request->$request->name_group){
                $baseQuery->where('name_group', '=', $request->$request->name_group);
             }       
                  
              // ->where('name_group', '=', $name_group);
                $group = $baseQuery->get();
                $response = array('code' => 200, 'animals' =>  $group);
               // var_dump($group);
          die;
                if (isset($request)) {
                    if (!$request->id_user) array_push($response['error_msg'], 'id_user name is required');
                    if (!count($response['error_msg']) > 0) {
                        
                        //TODO - TO TEST
            
                            if (!count($response['error_msg']) > 0) {
                                try {
                               
                                    $adminParcitipant = new Participant();
                                
                                    $adminParcitipant->id_user = $request->id_user;
                                    $adminParcitipant->id_group = $group->id;
                                    $adminParcitipant->admin_user_group = 0;
                                    $adminParcitipant->points = 0;
                                    $adminParcitipant->own_budget = $group->budget;
                                    $adminParcitipant->save();
        
                                    $response = array('code' => 200, 'Admin Participant' => $adminParcitipant, 'msg' => 'Group admin created');
                                
                                
                                } catch (\Exception $exception) {
                                    $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                                }
        
                            }
        
        
                    } else {
                        $response['error_msg'] = 'Please introduce the required data';
                    }
        
                } else {
                    $response['error_msg'] = 'Nothing to create';
                }
        
                return response($response,$response['code']);

        }catch(\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
    

     
    }

}
