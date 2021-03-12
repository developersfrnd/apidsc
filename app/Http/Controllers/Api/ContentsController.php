<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use App\Content;
use App\Http\Resources\ContentResource;

class ContentsController extends APIController
{
    public function bySlug($slug)
    {
        return $this->sendResponse(new ContentResource(Content::where('slug',$slug)->first()), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }
}
