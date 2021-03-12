<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['title', 'description'];

}
