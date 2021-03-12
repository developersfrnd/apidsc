<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Admin\AdminsController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Admin\VideoResource;
use \App\Http\Resources\Admin\VideoCollection;
use App\Video;

class VideosController extends AdminsController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if(request()->query('model_id')){
            $videos = Video::where('user_id',request()->query('model_id'))->orderBy('id','desc');
        }else{
            $videos = Video::orderBy('id','desc');
        };
        
        return new VideoCollection($videos->paginate(config('constant.pagination.per_page')));
    }
}
