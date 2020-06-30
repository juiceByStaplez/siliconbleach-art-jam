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

Route::get('/authenticate', function(Request $request) {
        $votes = $request->query('votes');

        $request->session()->put('votes', $request->query('votes'));

        return Socialite::with('twitch')->stateless()->redirect();
});

Route::get('/oauth/redirect', function (Request $request) {

    $votes = $request->session()->get('votes');
    $votes = explode(', ', $votes);


    $KOKO_STREAMER_ID = 104674657;
    $IRIS_STREAMER_ID = 52191499;
    $GREPO_STREAMER_ID = 115202117;
    $RUSSELL_STREAMER_ID = 184003841;

    $twitchOAuthUser = \Laravel\Socialite\Facades\Socialite::driver('twitch')->stateless()->user();

    $streamerIds = [$GREPO_STREAMER_ID, $RUSSELL_STREAMER_ID, $KOKO_STREAMER_ID, $IRIS_STREAMER_ID];

    // save for later, may need to store
    //    $accessTokenResponseBody = $user->accessTokenResponseBody;

    $email = $twitchOAuthUser->getEmail();

    $preExistingUser = User::with('votes')->where('email', $email)->first();

    $twitchId = $preExistingUser->twitch_id;
    if (!$preExistingUser) {
        $username = $twitchOAuthUser->getNickname();
        $twitchId = $twitchOAuthUser->getId();
        $token = $twitchOAuthUser->accessTokenResponseBody['access_token'];

        $client = new \romanzipp\Twitch\Twitch;

        $client->setClientId(config('twitch-api.client_id'));
        $client->setToken($token);

        $followsStreamers = collect($streamerIds)->reduce(function ($followsAHost, $hostId) use ($client, $twitchId) {
            if (!$followsAHost) {
                $result = $client->getFollows($twitchId, $hostId);
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

        $twitchOAuthUser = User::create([
            'email'       => $email,
            'nickname'    => $twitchOAuthUser->getNickname(),
            'name'        => $twitchOAuthUser->getName(),
            'password'    => Hash::make(\Illuminate\Support\Str::random(16)),
            'twitch_id'   => (int)$twitchId,
            'avatar'      => $twitchOAuthUser->getAvatar(),
            'voterStatus' => $followsStreamers,
        ]);


    }

    $createdVotes = [];
    foreach ($votes as $vote) {
        $createdVote = Vote::create([
            'user_id'  => $twitchOAuthUser->id,
            'piece_id' => $vote
        ]);

        $createdVotes[] = $createdVote;
    }

    return redirect()->away(config('app.contest_url'), ['user' => $user, 'votes' => $votes]);
});

Route::resource('votes', 'VotesController');

Route::get('/', function (Request $request) {
    dd($request->session()->get('votes'));
    return view('welcome');
});
