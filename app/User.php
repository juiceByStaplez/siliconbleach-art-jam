<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const CANNOT_VOTE_STATUS = 0;
    const FOLLOWS_STREAMERS_STATUS = 1;
    const AGE_TEST_FAILED_STATUS = 2;
    const FOLLOWER_COUNT_FAILED_STATUS = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'password', 'twitch_id', 'avatar', 'nickname', 'voterStatus'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function votes() {
        return $this->hasMany('App\Vote');
    }
}
