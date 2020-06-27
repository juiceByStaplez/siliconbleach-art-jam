<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

const KOKO_STREAMER_ID = 104674657;
const IRIS_STREAMER_ID = 52191499;
const GREPO_STREAMER_ID = 115202117;
const RUSSELL_STREAMER_ID = 184003841;

Route::get('/oauth/redirect', function (Request $request) {
    $user = \Laravel\Socialite\Facades\Socialite::driver('twitch')->user();
    $streamerIds = [GREPO_STREAMER_ID, RUSSELL_STREAMER_ID, KOKO_STREAMER_ID, IRIS_STREAMER_ID];
    $accessTokenResponseBody = $user->accessTokenResponseBody;

    $email = $user->getEmail();
    $username = $user->getNickname();
    $twitchId = $user->getId();
    $token = $user->accessTokenResponseBody['access_token'];


    $client = new \romanzipp\Twitch\Twitch;

    $client->setClientId(config('twitch-api.client_id'));
    $client->setToken($token);


    $result = $client->getFollows($twitchId, $streamerIds[1]);

    $followsStreamers = collect($streamerIds)->reduce(function ($followsAHost, $hostId) use ($client, $twitchId) {
        if (!$followsAHost) {
            print_r("Checking for followers \n\r");
            $result = $client->getFollows($twitchId, $hostId);
            print_r("Received response");
            if (count($result->data()) !== 0) {
                $followsAHost = true;
            } else {
                print_r("Does not follow at least one streamer");
            }
        }
        return $followsAHost;
    }, false);



    $data = $result;

    dd($data);


});

Route::resource('votes', 'VotesController');

Route::get('/', function (Request $request) {

    return view('welcome');
});
