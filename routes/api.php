<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user/{twitchId}/votes', 'UserController@votes');
Route::post('/votes', 'VotesController@store');

