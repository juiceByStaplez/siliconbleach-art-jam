<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function votes(Request $request, $twitchId)
    {
        $user = User::with('votes')->where('twitch_id', $twitchId)->first();

        if(!$user) {
            return Response::json(['data' => []], 404);
        }

        return Response::json(['user' => $user]);


    }
}
