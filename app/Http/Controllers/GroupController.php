<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Participant;
use Illuminate\Support\Facades\DB;
class GroupController extends Controller
{
 
    public function newGroup(Request $request)
    {
       
        $response = array('code' => 400, 'error_msg' => []);

        if (isset($request)) {
        
            if (!$request->name_group) array_push($response['error_msg'], 'Name is required');
            if (!$request->budget) array_push($response['error_msg'], 'Budget is required');
            if (!$request->gender) array_push($response['error_msg'], 'Gender is required');
            if (!$request->weight) array_push($response['error_msg'], 'Weight is required');
            if (!$request->id_user) array_push($response['error_msg'], 'Id_group name is required');
            if (!$request->password_group) array_push($response['error_msg'], 'Id_group name is required');
            if (!count($response['error_msg']) > 0) {
                
                //TODO - TO TEST
    
                    if (!count($response['error_msg']) > 0) {
                        try {
                            $group = new Group();
                            $group->name_group = $request->name_group;
                            $group->budget = $request->budget;
                            $group->gender = $request->gender;
                            $group->weight = $request->weight; /// ARREGLAR ERROR WEIGHT SIEMPRE APARECE -84
                            $group->id_user = $request->id_user; /// ARREGLAR ERROR ID SIEMPRE APARECE 1
                            $group->password_group = hash('sha256', $request->password_group);
                            $group->save();


                            $adminParcitipant = new Participant();
                            $adminParcitipant->own_budget = $request->budget;
                            $adminParcitipant->id_user = $request->id_user;
                            $adminParcitipant->points = $request->points;
                            $adminParcitipant->admin_user_group = 1;

                            $adminParcitipant->id_group = $group->id;

                      
                            $adminParcitipant->save();

                            $response = array('code' => 200, 'group' => $group, 'msg' => 'Group created', 'Admin Participant' => $adminParcitipant, 'msg' => 'Group admin created');
                        
                        
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
    }
//No funciona con el admin_user_group
    public function deleteGroup($id, Request $request){
        if (isset($id)) {
            //TODO - TO TEST
            try {
                $group = Group::find($id);

                if (!empty($group) && $request->admin_user_group === 1) {
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
}
