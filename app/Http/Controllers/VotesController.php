<?php

namespace App\Http\Controllers;

use App\Vote;
use App\User;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Whoops\Exception\ErrorException;
use Illuminate\Database\QueryException;

class VotesController extends Controller
{
    const MYSQL_DUPLICATE_KEY_ERROR_CODE = 1062;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request) {

        $siteURL = config('app.contest_url');

        $twitchId = $request->session()->pull('twitchId');

        $user = User::where('twitch_id', $twitchId)->first();

        if ($user) {

            $votes = $request->session()->pull('votes');
            $votes = explode(',', $votes);


            $createdVotes = [];
            $failedVotes = [];
            $hasDupes = false;
            foreach ($votes as $vote) {
                $createdVote = null;
                $createVote = [
                    'user_id'  => $user->id,
                    'piece_id' => $vote,
                ];
                try {

                    $createdVote = Vote::create($createVote);
                } catch(QueryException $e) {
                    if($e->getCode() === self::MYSQL_DUPLICATE_KEY_ERROR_CODE ) {
                        Log::info('Mysql duplicate key error');
                        Log::error($e->getMessage());
                        $hasDupes = true;
                    }

                }
                if(!empty($createdVote)) {
                    $failedVotes[] = $createVote;
                }

                if(!$hasDupes && !empty($failedVotes)) {
                    Log::info('Votes failed, reason unsure:');

                    Log::info(json_encode($failedVotes, JSON_PRETTY_PRINT));

               }
            }

            $successfullyVotedRedirectURL = "$siteURL?success=true&twitch_id={$twitchId}";
            return redirect()->away($successfullyVotedRedirectURL)->cookie('userTwitchId', $twitchId, $siteURL);
        } else {
            return response()->json(['success' => false, 'message' => 'Unable to find user with that ID'], 404);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $votes  = $request->votes;
        $userId = $request->user['id'];

        // check for deletes

        $userVotes      = Vote::where('user_id', $userId)->get()->pluck('piece_id');
        $deletableVotes = $userVotes->diff($votes)->all();

        foreach ($votes as $vote) {
            $updatedVotes = Vote::updateOrCreate(
                ['user_id' => $userId, 'piece_id' => $vote],
                ['piece_id' => $vote]
            );
        }

        $deleteVotes = Vote::whereIn('piece_id', $deletableVotes)->delete();

        return Response::json(['success' => true], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Vote $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Vote $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Vote $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Vote $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
