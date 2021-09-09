<?php

use Illuminate\Support\Facades\Route;
use App\Mail\UserSignup;
use Illuminate\Support\Facades\Mail;
use App\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = User::find(8);
    Mail::to("user9@yopmail.com")->send(new UserSignup($user));
    return view('welcome');
});
