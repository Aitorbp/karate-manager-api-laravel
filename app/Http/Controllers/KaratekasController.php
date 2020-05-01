<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Karateka;
use Illuminate\Support\Facades\Storage;
class KaratekasController extends Controller
{
    public function createKarateka(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (isset($request)) {

        
            if (!$request->name) array_push($response['error_msg'], 'Name is required');
            if (!$request->country) array_push($response['error_msg'], 'Country is required');
            if (!$request->gender) array_push($response['error_msg'], 'Gender is required');
            if (!$request->weight) array_push($response['error_msg'], 'Weight is required');

            // if (!$request->file('photo_karateka')) {
            //     array_push($response['error_msg'], 'Picture is required');
            // } else {
            //     $path = $request->file('photo_karateka')->store("picture");
            // };

            // if (!$request->file('photo_country')) {
            //     array_push($response['error_msg'], 'photo_country is required');
            // } else {
            //     $path = Storage::get($request->file('photo_country')->store("picture/flags"));
             
            // };
           
            if (!count($response['error_msg']) > 0) { //cambiar esto
                try {
                    $karateka = Karateka::where('name', '=', $request->name);

                    if (!$karateka->count()) {

                    $karateka = new Karateka();
                    $karateka->name = $request->name;
                    $karateka->country = $request->country;
                    $karateka->gender = $request->gender;
                    $karateka->weight = $request->weight;
                    $karateka->value = $request->value;
                    // $karateka->photo_karateka = $request->photo_karateka;
                    // $karateka->photo_country = $request->photo_country;

                    $karateka->save();
                    $response = array('code' => 200, 'karateka' => $karateka, 'msg' => 'Karateka created');
                }else {
                    $response = array('code' => 400, 'error_msg' => "Karateka already registered in this group.");
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

    public function deleteKarateka($id){
        if (isset($id)) {
            //TODO - TO TEST
            try {
                $karateka = Karateka::find($id);

                if (!empty($karateka)) {
                    try {
                        $karateka->delete();
                        $response = array('code' => 200, 'msg' => 'karateka deleted');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
        
                } else {
                    $response = array('code' => 401, 'error_msg' => 'Karateka not found');
                }

            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        }
       
       return response($response,$response['code']);
    }
    public function updateKarateka(Request $request, $id)
    {
        $response = array('code' => 400, 'error_msg' => []);

        if (isset($request) && isset($id)) {
            //TODO - TO TEST
            try {
                $karateka = Karateka::find($id);

                if (!empty($karateka)) {
                    try {
                        $karateka->name  = $request->name ? $request->name : $karateka->name;
                        $karateka->country = $request->country ? $request->country : $karateka->country;
                        $karateka->gender = $request->gender ? $request->gender : $karateka->gender;
                        $karateka->weight = $request->weight ? $request->weight : $karateka->weight;
                        $karateka->photo_karateka = $request->photo_karateka ? $request->photo_karateka : $karateka->photo_karateka;
                        $karateka->photo_country = $request->photo_country ? $request->photo_country : $karateka->photo_country;

                        //TODO - Find another way 
                        if ($request->file('photo_karateka')) {
                            $path = $request->file('photo_karateka')->store("photo_karateka");
                            $karateka->photo_karateka = $path;
                        }

                        if ($request->file('photo_country')) {
                            $path = $request->file('photo_country')->store("photo_country");
                            $karateka->photo_karateka = $path;
                        }

                        $karateka->save();
                        $response = array('code' => 200, 'msg' => 'Karateka updated');
                    } catch (\Exception $exception) {
                        $response = array('code' => 500, 'error_msg' => $exception->getMessage());
                    }
                } else {
                    $response['error_msg'] = 'No Karateka to update';
                }
            } catch (\Throwable $th) {
                $response = array('code' => 500, 'error_msg' => $exception->getMessage());
            }
        } else {
            $response['error_msg'] = 'Nothing to update';
        }

        return response($response, $response['code']);
    }
}
