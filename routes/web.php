<?php

use App\User;
use App\Vote;
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


Route::get('/oauth/redirect', function (Request $request) {

    $votesJSON = '["3_17_2_1_1593228677647_215","3_17_2_1_1593228677647_216"]';
    $votes = json_decode($votesJSON);


    $KOKO_STREAMER_ID = 104674657;
    $IRIS_STREAMER_ID = 52191499;
    $GREPO_STREAMER_ID = 115202117;
    $RUSSELL_STREAMER_ID = 184003841;

    $user = \Laravel\Socialite\Facades\Socialite::driver('twitch')->user();
    $streamerIds = [$GREPO_STREAMER_ID, $RUSSELL_STREAMER_ID, $KOKO_STREAMER_ID, $IRIS_STREAMER_ID];
    $accessTokenResponseBody = $user->accessTokenResponseBody;

    $email = $user->getEmail();

    $preexistingUser = User::where('email', $email)->first();

    if (!$preexistingUser) {
        $username = $user->getNickname();
        $twitchId = $user->getId();
        $token = $user->accessTokenResponseBody['access_token'];

        $client = new \romanzipp\Twitch\Twitch;

        $client->setClientId(config('twitch-api.client_id'));
        $client->setToken($token);

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

        if (!$followsStreamers) {
            return Response::json(['success' => false, 'message' => "{$username} is not following any of the hosts"]);
        }

        $newUser = User::create([
            'email' => $email,
            'nickname' => $user->getNickname(),
            'name' => $user->getName(),
            'password' => Hash::make(\Illuminate\Support\Str::random(16)),
            'twitch_id' => $twitchId,
            'avatar' => $user->getAvatar(),
        ]);

        $createdVotes = [];
        foreach($votes as $vote){
            $createdVote = Vote::create([
                'user_id' => $newUser->id,
                'piece_id' => $vote
            ]);

            $createdVotes[] = $createdVote;
        }

    }
});

Route::resource('votes', 'VotesController');

Route::get('/', function (Request $request) {
    dd($request->session()->get('votes'));
    return view('welcome');
});
