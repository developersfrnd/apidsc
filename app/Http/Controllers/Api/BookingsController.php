<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use \App\Booking;
use App\Http\Resources\BookingResource;
use App\Http\Resources\Bookings;

class BookingsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Bookings(request()->user()->bookings()->orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = new Booking;
        
        $booking->user_id = $request->user()->id;
        $booking->model_id = $request->model_id;
        $booking->creditPoints = (integer) $request->creditPoints;
        $booking->schedule_id = $request->schedule_id;
        $booking->save();

        $request->user()->creditPoints = $request->user()->creditPoints - $booking->creditPoints;
        $request->user()->save();

        return $this->sendResponse(new BookingResource($booking), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
