<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use \App\Http\Controllers\Api\Admin\AdminsController;
use App\Content;
use App\Http\Resources\Admin\ContentResource;
use App\Http\Resources\Admin\ContentCollection;


class ContentsController extends AdminsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(new ContentCollection(Content::all()), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $data = $request->all();        
        //$data['title'] = ucfirst(strtolower($request->title));
        $category = Content::create($data);
        return $this->sendResponse(new ContentResource($category), trans('responses.create',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        return $this->sendResponse(new ContentResource($content), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        $data = $request->all();        
        $content->update($data);
        return $this->sendResponse(new ContentResource($content), trans('responses.update',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
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
