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
    Route::post('/user/resend', 'AuthController@resend');
    Route::post('/login','AuthController@login');
    Route::get('/categories','CategoriesController@index');
    Route::get('/ethnicities','EthnicitiesController@index');
    Route::get('/languages','LanguagesController@index');
    Route::resource('videos', 'VideosController')->only(['index']);
    Route::get('galleries/{userId}', 'GalleriesController@show');
    Route::get('/contents/{slug}','ContentsController@bySlug');
    Route::get('/chat','WebSocketController@onOpen');

    Route::resource('users','UsersController')->only(['index', 'show']);
    
    Route::middleware('auth:api')->group(function() {
        Route::post('logout', 'AuthController@logout');
        Route::post('contactus', 'AuthController@contactUs');
        Route::get('user', 'AuthController@user');
        Route::get('galleries', 'GalleriesController@index');
        Route::post('galleries', 'GalleriesController@store');
        Route::resource('videos', 'VideosController')->only(['store','show']);
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
        Route::post('account','AccountsController@store');
        
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
            });
        });
    });
});