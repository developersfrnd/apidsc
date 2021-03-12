<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\APIController;
use Illuminate\Http\Request;
use App\Setting; 
use App\Http\Resources\SettingResource;

class SettingsController extends APIController
{
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->all();        
        $Setting->update($data);
        return $this->sendResponse(new SettingResource($setting), trans('responses.update',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }
}
