<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ResultKarateka;
use App\ResultParticipant;
use App\Karateka;
use App\Championship;
use App\Sale;
use App\Participant;


class ResultParticipantsController extends Controller
{
    public function getResultByChampGroup($id_championship, $id_group)
    {
        $response = array('code' => 400, 'error_msg' => []);
        if (!$id_group) array_push($response['error_msg'], 'id_karateka is required');
        if (!$id_championship) array_push($response['error_msg'], 'id_championship is required');
    
        $participantsResults = Participant::where('id_group', '=', $id_group)
        ->leftJoin ('result_participants', function ($join) use ($id_championship) {
            $join->on('participants.id', '=', 'result_participants.id_participant')
            ->where('result_participants.id_championship', '=', $id_championship );
        })
            
       
        ->select('participants.*',   'result_participants.id_championship as id_championship')

        ->get();
        
        
        //Con esta query sumamos los puntos de los ids duplicados
        $participantsResults
        ->groupBy('id')->flatMap(function ($items) {

            $total_points_in_championship = $items->sum('points_karateka');

            return $items->map(function ($item) use ($total_points_in_championship) {

                $item->total_points_in_championship = $total_points_in_championship;

                return $item;

            });

        });
      

        //Eliminamos los duplicados para que nos aparezca una tabla limpia con los puntos de los participantes por cada grupo
        $unique = $participantsResults->unique();
         $unique->values()->all();


        $response = array('code' => 200, 'resultKarateka' => $unique, 'msg' => 'resultKarateka created');
        return response($response, $response['code']);
    }
}
