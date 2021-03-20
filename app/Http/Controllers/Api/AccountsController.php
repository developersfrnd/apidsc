<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Account;

class AccountsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account = Account::where('user_id',request()->user()->id);
        if($account->count() > 0){
            return $this->sendResponse(new AccountResource($account->first()),trans('responses.msgs.feedComment'), config('constant.header_code.ok'));
        }else{
            return $this->sendResponse('',trans('responses.msgs.feedComment'), config('constant.header_code.ok'));
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountRequest $request)
    {
        $account = Account::firstOrNew([
            'user_id' => $request->user()->id
        ]);

        $account->account_name = $request->account_name;
        $account->bank_name = $request->bank_name;
        $account->account_number = $request->account_number;
        $account->ifsc_code = $request->ifsc_code;
        $account->save();

        $request->user()->cherges = $request->charge_per_minute;
        $request->user()->save();
        return $this->sendResponse(new AccountResource(Account::find($account->id)), trans('responses.msgs.feedComment'), config('constant.header_code.ok'));
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
