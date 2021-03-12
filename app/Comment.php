<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'comments', 'model_id', 'rating'
    ];

    public function users() {
        return $this->belongsTo('App\User');
    }
}
