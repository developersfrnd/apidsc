<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\GalleryResource;
use \App\Http\Resources\GalleryCollection;
use App\Traits\Images;
use App\Gallery;

class GalleriesController extends APIController {

    use Images;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return new GalleryCollection(Gallery::whereUserId(request()->user()->id)->orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
                
                $uploadedMedia = [];
                foreach ($request->file('photos') as $photo) {
                    $saved_file_name = $this->storeImage($photo,config('constant.paths.AVATARS'));
                    $uploadedMedia[] = [
                        'name' => $saved_file_name,
                        'tag' => $request->tag,
                        'title' => $request->title,
                        'description' => $request->description,

                    ];
                }    
                
                if (count($uploadedMedia)) {
                        $media = $request->user()->galleries()->createMany($uploadedMedia);
                }
                
                return $this->sendResponse('', trans('responses.msgs.success'), config('constant.header_code.ok'));
            
            } catch (Exception $exc) {
            
            return $this->sendError($this->exceptionMsg($exp),config('constant.header_code.exception'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FeedMedia  $feedMedia
     * @return \Illuminate\Http\Response
     */
    public function show($userId) {
        return new GalleryCollection(Gallery::whereUserId($userId)->orderBy('id','desc')->get());
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
    public function destroy($id) {
        Gallery::destroy($id);
        return $this->sendResponse('', trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

}
