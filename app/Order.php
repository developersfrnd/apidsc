<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','credits','pricePerCredit','amount','transction_id','paymentIntent'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}

