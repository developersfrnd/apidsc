<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use \App\Order;
use App\Http\Resources\Admin\OrderResource;
use App\Http\Resources\Admin\Orders;

class OrdersController extends AdminsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Orders(Order::orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
    }
}
