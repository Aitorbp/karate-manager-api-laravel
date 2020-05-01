<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResultKarateka;
use App\Karateka;
use App\Championship;
use Illuminate\Support\Facades\DB;
class ResultKaratekaController extends Controller
{
    public function addResultKarateka(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (isset($request)) {

        
            if (!$request->id_karateka) array_push($response['error_msg'], 'id_karateka is required');
            if (!$request->id_championship) array_push($response['error_msg'], 'id_championship is required');
            if (!$request->points) array_push($response['error_msg'], 'points is required');
           
            if (!count($response['error_msg']) > 0) { //cambiar esto
                try {
                    $resultKarateka = ResultKarateka::where('id_karateka', '=', $request->id_karateka)->where('id_championship', '=', $request->id_championship);

                    if (!$resultKarateka->count()) {

                    $resultKarateka = new ResultKarateka();
                    $resultKarateka->id_karateka = $request->id_karateka;
                    $resultKarateka->id_championship = $request->id_championship;
                    $resultKarateka->points = $request->points;
                    $resultKarateka->injured = $request->injured;
                    $resultKarateka->discontinued = $request->discontinued;
                    $totalPoints = DB::table('result_karatekas')->where('id_karateka', '=', $request->id_karateka)->sum('points'); //SUM ALL POINTS BY KARATEKA

                    $resultKarateka->points_total = $totalPoints + $request->points;
                    $resultKarateka->save();

                    //Asignar puntos totales en la tabla de Karateka
                    $karateka = Karateka::find($request->id_karateka);  
                    $karateka->points_karateka = $resultKarateka->points_total;
                    $karateka->save();

                    $response = array('code' => 200, 'resultKarateka' => $resultKarateka, 'msg' => 'resultKarateka created');
                }else {
                    $response = array('code' => 400, 'error_msg' => "resultKarateka already registered in this group.");
                }
                } catch (\Exception $exception) {
                    $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                }
            }
        } else {
            $response['error_msg'] = 'Nothing to create';
        }

        return response($response, $response['code']);
    }

    public function getAllResultByKarateka($id)
    {
        $response = array('code' => 400, 'error_msg' => []);
        try {
            $karateka = Karateka::find($id);
            if (!empty($karateka)) {
                
            $karatekaChampionship = DB::table('result_karatekas')->select('id_championship')->where('id_karateka', '=', $karateka->id)->get();
    
               // $championship = DB::table('championship')->where('id', '=', $karatekaChampionship->id_championship)->get();
                $results = DB::table('result_karatekas')->where('id_karateka', '=', $karateka->id)
              
                ->join('championship','result_karatekas.id_championship', '=',  'championship.id')
                
                ->select('championship.*', 'result_karatekas.*')
           
                ->get();

                return   $response = array('code' => 200,'Karateka'=>$karateka, 'results' => $results, 'msg' => 'Get all result');
      
           

            } else {
                $response = array('code' => 401, 'error_msg' => 'Unautorized');
            }
        } catch (\Exception $exception) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
        }
        return response($response, $response['code']);

    }
}
