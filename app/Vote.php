<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getFormattedPieceIdAttribute()
    {
        $id = last(explode("_", $this->piece_id));
        return (int)$id;
    }
}
