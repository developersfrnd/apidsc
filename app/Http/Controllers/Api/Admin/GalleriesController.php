<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Admin\AdminsController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Admin\GalleryResource;
use \App\Http\Resources\Admin\GalleryCollection;
use App\Gallery;

class GalleriesController extends AdminsController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if(request()->query('model_id')){
            $photos = Gallery::where('user_id',request()->query('model_id'))->orderBy('id','desc');
        }else{
            $photos = Gallery::orderBy('id','desc');
        };
        
        return new GalleryCollection($photos->paginate(config('constant.pagination.per_page')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
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
    public function destroy(FeedMedia $feedMedia) {
        //
    }

}
