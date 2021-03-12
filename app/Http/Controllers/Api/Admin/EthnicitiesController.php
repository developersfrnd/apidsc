<?php

namespace App\Http\Controllers\API\Admin;

use App\Ethnicity;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Admin\AdminsController;
use App\Http\Resources\Admin\EthnicityResource;
use App\Http\Resources\Admin\EthnicityCollection;

class EthnicitiesController extends AdminsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(new EthnicityCollection(Ethnicity::all()), trans('responses.msgs.success'), config('constant.header_code.ok'));
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
        $ethnicity = Ethnicity::create($data);
        return $this->sendResponse(new EthnicityResource($ethnicity), trans('responses.create',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ethnicity $ethnicity)
    {
        return $this->sendResponse(new EthnicityResource($ethnicity), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ethnicity $ethnicity)
    {
        $data = $request->all();        
        $data['name'] = ucfirst(strtolower($request->name));
        $ethnicity->update($data);
        return $this->sendResponse(new EthnicityResource($ethnicity), trans('responses.update',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
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
