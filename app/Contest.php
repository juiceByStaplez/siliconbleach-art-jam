<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    public $guarded = [];

    public function entries()
    {
        return $this->hasMany('App\Piece::class');
    }
}
