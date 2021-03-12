<?php

namespace App\Http\Controllers\Api\Admin;

use App\Order;
use App\User;
use App\Gallery;
use App\Video;
use Illuminate\Http\Request;
use \App\Http\Controllers\Api\Admin\AdminsController;

class DashboardController extends AdminsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['models'] = User::where('role',config('constant.userrole.model'))->count();
        $data['customers'] = User::where('role',config('constant.userrole.customer'))->count();;
        $data['orderValue'] = Order::all()->sum('amount');
        $data['orders'] = Order::all()->count();
        $data['videos'] = Video::all()->count();
        $data['photos'] = Gallery::all()->count();
        
        return $this->sendResponse($data,trans('responses.msgs.success'));
    }
}
