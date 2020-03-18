<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Participant;
use App\Group;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    

    public function addParticipant(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if ($request->name_group && $request->password_group) {
            //TODO - TO TEST
            try {
                $group = Group::where('name_group', "$request->name_group")->first();

                if (!empty($group)) {
                    if ($group->password_group === hash('sha256', $request->password_group)) {
                        try {
                        
                            $group->get();

                            if (!$request->id_user){
                                array_push($response['error_msg'], 'id_user name is required');
                            } else{
                                $adminParcitipant = new Participant();
                                
                                $adminParcitipant->id_user = $request->id_user;
                                $adminParcitipant->id_group = $group->id;
                                $adminParcitipant->admin_user_group = 0;
                                $adminParcitipant->points = 0;
                                $adminParcitipant->own_budget = $group->budget;
                                $adminParcitipant->save();
    
                                $response = array('code' => 200, 'Participant' => $adminParcitipant, 'msg' => 'Participant created');
                            }
               
                        } catch (\Exception $exception) {
                            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                        }
                    } else {
                        $response['error_msg'] = 'Wrong password';
                    }
                } else {
                    $response['error_msg'] = 'Group not found';
                }
            } catch (\Exception $exception) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
            

        } else {
            $response['error_msg'] = 'Email and password are required';
        }

        return response($response,$response['code']);
    }
     
     
}
