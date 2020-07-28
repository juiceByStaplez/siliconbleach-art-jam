<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VotesController;


Route::get('/user/{twitchId}/votes', 'UserController@votes');
Route::post('/votes', 'VotesController@store');

