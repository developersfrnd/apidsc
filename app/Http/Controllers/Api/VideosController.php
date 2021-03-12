<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Video as VideoResource;
use App\Http\Resources\ViewVideo as ViewVideoResource;
use App\Http\Requests\VideosRequest;
use \App\Http\Resources\Videos;
use App\Traits\Images;
use App\Video;

class VideosController extends APIController
{
    use Images;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if(request()->query('model_id')){
            return new Videos(Video::where('user_id',request()->query('model_id'))->orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
        }else{
            return new Videos(Video::orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VideosRequest $request) {
        try {
                $saved_video_file_name = $this->storeImage($request->file('video'),config('constant.paths.VIDEOS'));
                $saved_thumb_file_name = $this->storeImage($request->file('thumb'),config('constant.paths.AVATARS'));
                $uploadedMedia = [
                    'name' => $saved_video_file_name,
                    'creditPoints' => $request->creditPoints,
                    'duration' => $request->duration,
                    'title' => $request->title,
                    'thumb'=> $saved_thumb_file_name,
                    'description' => $request->description,
                ];
                
                if (count($uploadedMedia)) {
                        $media = $request->user()->videos()->create($uploadedMedia);
                }
                
                return $this->sendResponse($media, trans('responses.msgs.success'), config('constant.header_code.ok'));
            
            } catch (Exception $exc) {
            
            return $this->sendError($this->exceptionMsg($exp),config('constant.header_code.exception'));
        }
    }

    public function myVideos($userId) {
        return new Videos(Video::whereUserId(request()->user()->id)->orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\FeedMedia  $feedMedia
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video) {
        if(request()->user()->creditPoints < $video->creditPoints){
            return $this->sendError('You must have '.$video->creditPoints.' credit points to view this video.','200');
        }
        return $this->sendResponse(new VideoResource($video), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FeedMedia  $feedMedia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeedMedia $feedMedia) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FeedMedia  $feedMedia
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedMedia $feedMedia) {
        //
    }
}
