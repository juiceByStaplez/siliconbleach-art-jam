<?php

namespace App\Http\Controllers;

use App\Vote;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use romanzipp\Twitch\Twitch;
use Illuminate\Http\Response;
use romanzipp\Twitch\Enums\Scope;
use Illuminate\Support\Facades\Hash;
use romanzipp\Twitch\Enums\GrantType;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;

const MINUTES_IN_A_MONTH = 43200;

class OAuthController extends Controller
{


    public function authenticate(Request $request)
    {
        $votes = $request->query('votes');

        $storeInSession = Cookie::make('votes', $votes, MINUTES_IN_A_MONTH, '', $_SERVER['HTTP_X_ORIGINAL_HOST']);

        request()->session()->flash('votes', $votes);

        $client = new Twitch;
        $client->setClientId(config('twitch-api.client_id'));
        $client->setClientSecret(config('twitch-api.client_secret'));
        $client->setRedirectUri(config('twitch-api.redirect_url'));

        $authURL = $client->getOAuthAuthorizeUrl('code', ['user_read']);
        return Redirect::to($authURL, 302);
    }

    public function getToken(Request $request)
    {
        $siteURL = config('app.site_url');

        $KOKO_STREAMER_ID    = 104674657;
        $IRIS_STREAMER_ID    = 52191499;
        $GREPO_STREAMER_ID   = 115202117;
        $RUSSELL_STREAMER_ID = 184003841;

        $streamerIds = [$GREPO_STREAMER_ID, $RUSSELL_STREAMER_ID, $KOKO_STREAMER_ID, $IRIS_STREAMER_ID];

        $client = new Twitch();

        $getToken = $client->getOAuthToken($request->code, GrantType::AUTHORIZATION_CODE, [Scope::V5_USER_READ]);

        $authToken = '';
        if (!$getToken->success) {
            return Redirect::to($siteURL . '/jam?success=false');
        }

        $authToken = $getToken->data->access_token;
        $getUsers  = $client->withToken($authToken)->getusers();

        if (!$getUsers->success) {
            return Redirect::to($siteURL . '/jam?success=false');
        }

        $twitchUser = $getUsers->data[0];
        $twitchId   = $twitchUser->id;

        $user       = User::with('votes')->where('twitch_id', $twitchId)->first();


        if (!$user) {
            $username = $twitchUser->display_name;
            $twitchId = $twitchUser->id;

            $followsStreamers = collect($streamerIds)->reduce(function ($followsAHost, $hostId) use ($client, $twitchId) {
                if (!$followsAHost) {
                    $result = $client->getFollows($twitchId, $hostId);
                    if (count($result->data()) !== 0) {
                        $followsAHost = true;
                    }
                }
                return $followsAHost;
            }, false);

            if (!$followsStreamers) {
                return Response::json(['success' => false, 'message' => "{$username} is not following any of the hosts"]);
            }

            $user = User::create([
                'nickname'    => $twitchUser->login,
                'name'        => $twitchUser->display_name,
                'password'    => Hash::make(Str::random(16)),
                'twitch_id'   => (int)$twitchId,
                'avatar'      => $twitchUser->profile_image_url,
                'voterStatus' => $followsStreamers,
            ]);

        }

        $request->session()->flash('twitchId', $user->twitch_id);
        $request->session()->reflash();
        return redirect()->action('VotesController@create');

    }
}
