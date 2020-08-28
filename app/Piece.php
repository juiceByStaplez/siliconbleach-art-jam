<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    public $timestamps = false;
    public $guarded = [];

    public function artistUser()
    {
        return $this->hasOne('App\User::class');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote::class');
    }
}
