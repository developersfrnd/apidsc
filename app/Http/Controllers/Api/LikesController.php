<?php

namespace App\Http\Controllers\API;

use App\Feed;
use App\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use App\Http\Resources\LikeResource;
use App\Http\Resources\LikeCollection;

class LikesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $likes = $request->user()->userLikesTo()->toggle([$request->model_id]);
        return $this->sendResponse([], trans('responses.msgs.like'), config('constant.header_code.ok'));
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
    public function destroy(Feed $feed, $id)
    {
        $queryObj = Like::where('id', $id)->where('user_id', request()->user()->id)->first();
        if ($queryObj) {
            $queryObj->delete();
            $queryObj->feed->decrement('like_count');
            return $this->sendResponse(new LikeResource($queryObj), trans('responses.msgs.feedUnlike'), config('constant.header_code.ok'));
        } else {
            return $this->noContent();
        }
    }
}
