<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->group(function () {
    Route::post('/signup','AuthController@signup');
    Route::post('/isEmailAvalaible','AuthController@isEmailAvalaible');
    Route::post('/forgotpasswordtoken','AuthController@forgotpasswordtoken');
    Route::post('/setpassword','AuthController@setPassword');
    Route::post('/verify','AuthController@verify');
    Route::post('/user/resend', 'AuthController@resend');
    Route::post('/login','AuthController@login');
    Route::get('/categories','CategoriesController@index');
    Route::get('/ethnicities','EthnicitiesController@index');
    Route::get('/languages','LanguagesController@index');
    Route::get('/faqs','FaqsController@index');
    Route::resource('videos', 'VideosController')->only(['index']);
    Route::get('galleries/{userId}', 'GalleriesController@show');
    Route::get('/contents/{slug}','ContentsController@bySlug');
    Route::get('/chat','WebSocketController@onOpen');

    Route::resource('users','UsersController')->only(['index', 'show']);
    Route::post('contactus', 'AuthController@contactUs');

    Route::middleware('auth:api')->group(function() {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('galleries', 'GalleriesController@index');
        Route::delete('galleries/{id}', 'GalleriesController@destroy');
        Route::post('galleries', 'GalleriesController@store');
        Route::resource('videos', 'VideosController')->only(['store','show', 'destroy']);
        Route::post('orders/paymentIntent','OrdersController@getPaymentIntent');
        Route::resource('/orders','OrdersController');
        Route::resource('/bookings','BookingsController');
        Route::resource('/schedules','SchedulesController');
        Route::post('user/purchaseVideo','UsersController@purchaseVideo');
        Route::resource('likes','LikesController')->only(['index', 'store']);
        Route::resource('comments','CommentsController')->only(['index', 'store']);
        Route::resource('videoChatSessions','VideoChatSessionController');
        Route::get('user/videos','UsersController@videos');
        Route::resource('users','UsersController')->only(['update']);
        Route::get('user/online','UsersController@onlineuser');      
        Route::get('user/offline','UsersController@offlineuser');
        Route::post('checkusercoin','UsersController@checkusercoin');
        Route::post('reduceusercoin','UsersController@reduceusercoin');
        Route::resource('account','AccountsController')->only(['index', 'store']);
        Route::get('setting','SettingsController@show');
    });

    Route::prefix('admin')->group(function () {
        Route::namespace('Admin')->group(function () {
            Route::get('/','AuthController@index');
            Route::post('/login','AuthController@login');
            Route::middleware(['auth:api','userrole'])->group(function() {
                Route::get('/users/export', 'UsersController@export');
                Route::resource('/users','UsersController')->only(['index', 'update', 'show']);
                Route::post('logout', 'AuthController@logout');
                Route::get('dashboard', 'DashboardController@index');
                Route::resource('/categories','CategoriesController');
                Route::resource('/ethnicities','EthnicitiesController');
                Route::resource('/contents','ContentsController');
                Route::resource('/settings','SettingsController');
                Route::resource('/photos', 'GalleriesController')->only(['index']);
                Route::resource('/videos', 'VideosController')->only(['index']);
                Route::resource('/orders', 'OrdersController')->only(['index']);
                Route::resource('/faqs','FaqsController');
                Route::get('/user/block/{id}', 'UsersController@block');
                Route::get('/user/unblock/{id}', 'UsersController@unblock');
            });
        });
    });
});