<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['user_id','account_name','bank_name','account_number','ifsc_code'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
