<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Championship;
class ChampionshipController extends Controller
{
    public function createChampionship(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (isset($request)) {

        
            if (!$request->name) array_push($response['error_msg'], 'Name is required');
            if (!$request->host_country) array_push($response['error_msg'], 'host_country is required');
            if (!$request->start_day) array_push($response['error_msg'], 'start_day is required');
            if (!$request->finish_day) array_push($response['error_msg'], 'finish_day is required');
            if (!$request->file('photo')) {
                array_push($response['error_msg'], 'Picture is required');
            } else {
                $path = $request->file('photo')->store("photo");
            };
           
            if (!count($response['error_msg']) > 0) { //cambiar esto
                try {
                    $champinship = Championship::where('name', '=', $request->name);

                    if (!$champinship->count()) {

                    $champinship = new Championship();
                    $champinship->name = $request->name;
                    $champinship->host_country = $request->host_country;
                    $champinship->start_day = $request->start_day;
                    $champinship->finish_day = $request->finish_day;
                    $champinship->photo = $request->photo;
         
                    $champinship->save();
                    $response = array('code' => 200, 'champinship' => $champinship, 'msg' => 'champinship created');
                }else {
                    $response = array('code' => 400, 'error_msg' => "champinship already registered in this group.");
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
}
