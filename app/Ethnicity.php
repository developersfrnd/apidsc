<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    protected $fillable = ['name'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', config('constant.ACTIVE_STATUS'));
        });
    }

    public function user() {
        return $this->hasMany('App\User');
    }
}
