<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Faq;
use App\Http\Controllers\Api\APIController;
use App\Http\Resources\FaqResource;
use App\Http\Resources\FaqCollection;

class FaqsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(new FaqCollection(Faq::orderBy('updated_at', 'DESC')->get()), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

}
