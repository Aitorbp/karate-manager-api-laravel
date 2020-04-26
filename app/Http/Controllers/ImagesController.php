<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
class ImagesController extends Controller
{
    public function uploadImageGroup(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);
   
        if (!$request->id) array_push($response['error_msg'], 'Id_group  is required');
        if (!$request->file('picture_group')) {
            array_push($response['error_msg'], 'Picture is required');
        } else {
            $path = $request->file('picture_group')->store("picture");
        };

        $group = Group::find($request->id);
           try {
            if ($request->file('picture_group')) {
                $path = $request->file('picture_group')->store("picture");
                $group->picture_group = $path;
                $group->save();
                $response = array('code' => 200, 'msg' => 'Image updated');
            }
           } catch (\Throwable $th) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
           }

           return response($response, $response['code']);
    }
}
