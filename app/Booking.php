<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id','credits','pricePerCredit','amount','transction_id','paymentIntent'];

    public function schedule() {
        return $this->belongsTo('App\Schedule');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function model() {
        return $this->belongsTo('App\User','model_id');
    }
}

