<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Karateka;
use App\User;
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

    public function uploadImageKarateka(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);
   
        if (!$request->id) array_push($response['error_msg'], 'Id_karakteka  is required');
        if (!$request->file('photo_karateka')) {
            array_push($response['error_msg'], 'Picture is required');
        } else {
            $path = $request->file('photo_karateka')->store("picture");
        };

        $karateka = Karateka::find($request->id);
           try {
            if ($request->file('photo_karateka')) {
                $path = $request->file('photo_karateka')->store("picture");
                $karateka->photo_karateka = $path;
                $karateka->save();
                $response = array('code' => 200, 'msg' => 'Image karateka updated');
            }
           } catch (\Throwable $th) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
           }

           return response($response, $response['code']);
    }

    public function uploadImageUser(Request $request)
    {
        $response = array('code' => 400, 'error_msg' => []);
   
        if (!$request->id) array_push($response['error_msg'], 'Id_User  is required');
        if (!$request->file('photo_profile')) {
            array_push($response['error_msg'], 'Picture is required');
        } else {
            $path = $request->file('photo_profile')->store("picture");
        };

        $karateka = User::find($request->id);
           try {
            if ($request->file('photo_profile')) {
                $path = $request->file('photo_profile')->store("picture");
                $karateka->photo_profile = $path;
                $karateka->save();
                $response = array('code' => 200, 'msg' => 'Image user updated');
            }
           } catch (\Throwable $th) {
            $response = array('code' => 500, 'error_msg' => $exception->getMessage());
           }

           return response($response, $response['code']);
    }
}
