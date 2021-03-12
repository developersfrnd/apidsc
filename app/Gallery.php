<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    //
    protected $fillable = [
        'name', 'title', 'description', 'user_id', 'privacy', 'mediaType','tag'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
