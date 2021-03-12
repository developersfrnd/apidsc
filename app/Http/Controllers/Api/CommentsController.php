<?php

namespace App\Http\Controllers\API;

use App\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentCollection;
use DB;


class CommentsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //return new CommentCollection($feed->comments()->orderBy('created_at', 'desc')->paginate(config('constant.pagination.per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = request()->user()->comments()->create([
            'rating' => $request->rating,
            'comments' => $request->comments,
            'model_id' => $request->model_id
        ]);

        return $this->sendResponse(new CommentResource($result), trans('responses.msgs.feedComment'), config('constant.header_code.ok'));
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
    public function destroy(Comments $comment)
    {
        //
    }
}
