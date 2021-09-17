<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Faq;
use App\Http\Controllers\Api\Admin\AdminsController;
use App\Http\Resources\Admin\FaqResource;
use App\Http\Resources\Admin\FaqCollection;

class FaqsController extends AdminsController
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $data = $request->all();        
        $data['question'] = $request->question;
        $data['answer'] = $request->answer;
        $faq = Faq::create($data);
        return $this->sendResponse(new FaqResource($faq), trans('responses.create',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        return $this->sendResponse(new FaqResource($faq), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        $data = $request->all();        
        $data['question'] = $request->question;
        $data['answer'] = $request->answer;
        $faq->update($data);
        return $this->sendResponse(new FaqResource($faq), trans('responses.update',['key'=> trans('responses.keyText.category')]), config('constant.header_code.ok'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Faq::destroy($id);
        return $this->sendResponse('', trans('responses.msgs.success'), config('constant.header_code.ok'));
    }
}
