<?php

namespace App\Http\Controllers\Api\Admin;

use \App\Http\Controllers\Api\Admin\AdminsController;
use Illuminate\Http\Request;
use App\Setting;
use App\Http\Resources\Admin\SettingResource;


class SettingsController extends AdminsController
{
    public function show($id)
    {
        return $this->sendResponse(new SettingResource(Setting::first()), trans('responses.update',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->all();        
        $setting->update($data);
        return $this->sendResponse(new SettingResource($setting), trans('responses.update',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }
}
