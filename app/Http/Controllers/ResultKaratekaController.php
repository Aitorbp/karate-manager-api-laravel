<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResultKarateka;
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

    public function sumPointsByChamp()
    {
        $data = DB::table('result_karatekas')
        ->where('id_karateka', $request->id_karateka)
        ->groupBy('id_karateka')
        ->sum()
        ->get();
    }
}
