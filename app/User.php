<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function galleries() {
        return $this->hasMany('App\Gallery');
    }

    public function orders() {
        return $this->hasMany('App\Order');
    }

    public function videos() {
        return $this->hasMany('App\Video');
    }

    public function schedules() {
        return $this->hasMany('App\Schedule');
    }

    public function bookings() {
        return $this->hasMany('App\Booking');
    }

    public function VideoChatSession() {
        return $this->hasOne('\App\VideoChatSession');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function languages()
    {
        return $this->belongsToMany('App\Language');
    }

    public function ethnicities() {
        return $this->belongsTo('App\Ethnicity','ethnicity');
    }

    public function purchasedVideos() {
        return $this->belongsToMany('\App\Video');
    }

    public function userLikesTo() {
        return $this->belongsToMany('\App\User', 'likes', 'user_id', 'model_id');
    }

    public function modelLikes(){
        return $this->belongsToMany('\App\User', 'likes',  'model_id', 'user_id');
    }

    public function comments() {
        return $this->hasMany('\App\Comment');
    }
}
