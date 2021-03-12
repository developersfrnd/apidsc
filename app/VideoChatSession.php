<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoChatSession extends Model
{
    protected $fillable = [
        'token', 'token', 'channel','subscriber_id','subscriber_token'
    ];

    public function user() {
        return $this->belongsTo('\App\User');
    }
}
