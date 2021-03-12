<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $fillable = [
        'name', 'title', 'description', 'user_id', 'privacy', 'creditPoints','duration', 'thumb'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function purchasedByUsers() {
        return belongsToMany('\App\User');
    }
}
