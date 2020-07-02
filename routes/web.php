<?php

use App\User;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use romanzipp\Twitch\Twitch;


Route::get('/authenticate', 'OAuthController@authenticate');
Route::get('/oauth/redirect', 'OAuthController@getToken');

Route::resource('votes', 'VotesController');

