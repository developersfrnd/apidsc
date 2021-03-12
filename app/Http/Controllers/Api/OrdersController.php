<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use \App\Setting;
use \App\Order;
use App\Http\Resources\OrderResource;
use App\Http\Resources\Orders;

class OrdersController extends APIController
{
    function calculateOrderAmount($cp): int {
        $settings = Setting::first();
        return $cp*($settings->pricePerCredit)*100;   // 100, since stripe saves in cents. 
      }
    
    public function getPaymentIntent(Request $request){
        \Stripe\Stripe::setApiKey(config('constant.Payment.ApiKey'));
        $cp = $request->creditPoints;
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $this->calculateOrderAmount($cp),
            'currency' => config('constant.Payment.Currency'),
            'description' => 'Software development services',
            'metadata' => [
                'user_id' => $request->user()->id,
                'creditPoints'=> $cp
            ],
        ]);

        return json_encode(['publishableKey' => config('constant.Payment.publishableKey'),'clientSecret' => $paymentIntent->client_secret]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new Orders(request()->user()->orders()->orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $settings = Setting::first();
        $order = new Order;
        
        $order->user_id = $request->user_id;
        $order->amount = $request->amount;
        $order->credits = (integer) $request->credits;
        $order->pricePerCredit = $settings->pricePerCredit;
        $order->transction_id = $request->transction_id;
        $order->paymentIntent = json_encode($request->paymentIntent);
        $order->save();

        $user = $request->user();
        $user->creditPoints +=  $order->credits;
        $user->save();

        return $this->sendResponse(new OrderResource($order), trans('responses.msgs.success'), config('constant.header_code.ok'));
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
