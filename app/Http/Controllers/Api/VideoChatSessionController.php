<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use \App\VideoChatSession;
use App\Http\Resources\VideoChatSessionResource;

class VideoChatSessionController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $videochat = new VideoChatSession();
        $videochat->token = '00621d00563319d453682d9b93e2acde5d9IAC+D86lE6ztj9ReDuUJD9O7KK6sf6kmRRCxzHi2sYYanY9VQLUAAAAAEAANYBkGB3p7XwEAAQAHentf';
        $videochat->user_id = $this->getUser()->id;
        $videochat->channel = 'dsc'.$this->getUser()->id;
        $videochat->save();

        return $this->sendResponse(new VideoChatSessionResource($videochat), config('constant.msgs.success'), config('constant.header_code.ok'));
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
