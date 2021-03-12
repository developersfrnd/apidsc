<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use \App\Http\Controllers\Api\Admin\AdminsController;
use App\Category;
use App\Http\Resources\Admin\Category as CategoryResource;
use App\Http\Resources\Admin\CategoryCollection;


class CategoriesController extends AdminsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(new CategoryCollection(Category::all()), trans('responses.msgs.success'), config('constant.header_code.ok'));
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
        $data['name'] = ucfirst(strtolower($request->name));
        $category = Category::create($data);
        return $this->sendResponse(new CategoryResource($category), trans('responses.create',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->sendResponse(new CategoryResource($category), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->all();        
        $category->update($data);
        return $this->sendResponse(new CategoryResource($category), trans('responses.update',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
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
