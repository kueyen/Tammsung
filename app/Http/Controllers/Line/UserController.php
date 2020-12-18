<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function checkRegistered(Request $request)
    {
        $find = User::where('line_user_id', $request->line_user_id)->first();



        $isRegistered = $find ? true : false;

        return response()->json([
            'message' => '',
            'result' => [
                'isRegistered' =>  $isRegistered
            ],
        ]);
    }
}