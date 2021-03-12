<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['user_id','fromTime','toTime','creditPointsPerMinute'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function bookings() {
        return $this->hasMany('App\Booking');
    }
}
