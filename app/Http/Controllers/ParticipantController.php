<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Participant;
use App\Group;
use App\User;
use App\Sale;
use App\Karateka;
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
                            if (!$request->id_user){
                                array_push($response['error_msg'], 'id_user name is required');
                            } else{

                               
                                $parcitipant = Participant::where('id_user', '=', $request->id_user)->where('id_group', '=', $group->id);
                                if (!$parcitipant->count()) {
                                  
                                    $parcitipant = new Participant();
                                    $parcitipant->id_user = $request->id_user;
                                    $parcitipant->id_group = $group->id;
                                    $parcitipant->admin_user_group = 0;
                                    $parcitipant->points = 0;
                                    $parcitipant->own_budget = $group->budget;
                                    $parcitipant->save();

//var_dump($parcitipant);

                                    //INCORPORAR KARATEKAS RANDOM A PARTICIPANTE

                                    //Filtrar por grupo
                              
                                //    var_dump($group->id);
                                    $salesByGroup = DB::table('sales')->where('id_group', '=', $group->id)->get();

                              //      var_dump($salesByGroup);
                     
                                    $karatekas = Karateka::all();
                                    foreach ($salesByGroup  as $value) {
                                      $karatekas = $karatekas->where('id', '<>', $value->id_karatekas); // Get all the karateka that don't have that id 

                                    }
                                  //  var_dump($karatekas);
                                  //  die;
                                
                                    $karatekaRandom = $karatekas->random(8);
                                    //  var_dump($karatekaRandom);
                                    //  die;
                                    $startingKarateka = 0;

                                    foreach ($karatekaRandom as $key) {
                                        $sale = new Sale();
                                        $sale->id_group = $group->id;
                                        $sale->id_participants =  $parcitipant->id;
                                        $sale->id_karatekas = $key->id;
                                        $sale->bid_participant = $key->value;
                                        $sale->starting = $startingKarateka;
                                        $sale->save();
                                    }
                                    $response = array('code' => 200, 'Participant' => $parcitipant, 'karateka' => $karatekaRandom, 'msg' => 'Participant and sales created', );
                                }else {
                                    $response = array('code' => 400, 'error_msg' => "Participant already registered in this group.");
                                }
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


    public function getAllParticipantsByGroup($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        try {
            $group = Group::find($id);
            
            if (!empty($group)) {
              
                $results = DB::table('participants')
                ->where('id_group', '=', $group->id)
                ->join('users', 'users.id', '=' , 'participants.id_user' )
                ->select('participants.*','participants.id', 'users.email', 'users.name', 'photo_profile')->get();
            //    ->join('users', 'users.id', '=' , 'participants.id_users' );
             
                return   $response = array('code' => 200, 'participants' => $results, 'msg' => 'Get all participants by group');
            } else {
                $response = array('code' => 401, 'error_msg' => 'Unautorized');
            }
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);

    }


    public function getAllGroupByParticipant($id_user){
 
        $response = array('code' => 400, 'error_msg' => []);
        try {
            $participants = Participant::where('id_user','=',$id_user)->get();
            

            $groups = Group::all();
            $groupsByParticipant = array();
            foreach ($participants as $participant) {
                foreach ($groups as $group){
                  if($group->id == $participant->id_group) {

                    $groupsByParticipant[] = $group;
               
                  }
            
                }
            }
            $response = array('code' => 200, 'groupByParticipant' => $groupsByParticipant, 'msg' => 'Get all groups by participant');


          
           
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);
    }
/*
    public function getAsllParticipantsByGroup($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        try {
            $user = User::find($id);
            if (!empty($user)) {
          
                $participants = $user->participantsGroup;
                return   $response = array('code' => 200, 'participants' => $participants, 'msg' => 'Get all participants by group');
            } else {
                $response = array('code' => 401, 'error_msg' => 'Unautorized');
            }
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);

    }
*/
    public function deleteParticipant($id){
        if (isset($request) && isset($id)) {
            //TODO - TO TEST
            try {
                $participant = Participant::find($id);

                if (!empty($participant)) {
                    try {
                        $participant->delete();
                        $response = array('code' => 200, 'msg' => 'participant deleted');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
        
                } else {
                    $response = array('code' => 401, 'error_msg' => 'Unautorized');
                }

            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
       
       return response($response,$response['code']);
    }

    public function getParticipantByGroup( $id_user, $id_group){

        $response = array('code' => 400, 'error_msg' => []);

        if (isset($id_group) && isset($id_user)) {
           
            try {
                
                $participant = Participant::where('id_group', '=', $id_group)->where('id_user', '=', $id_user)->get();
                
                $response = array('code' => 200, 'participants' =>$participant,  'msg' => 'Get participant');
                

            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
        return response($response, $response['code']);
    }

     
}


