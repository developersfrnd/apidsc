<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class APIController extends Controller
{
    public function sendResponse($result,$message,$code=200,$extra_params=[])
    {
    	$response = [
            'status' =>true,
            'status_code' => $code,
            'data'    => $result,
            'message' => $message,
        ];
        
        if(count($extra_params)){
            $response = array_merge($response,$extra_params);
        }
        //,[], JSON_NUMERIC_CHECK
        return response()->json($response,$code);
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
    */
    public function sendError($errorMessages = [], $code = 404)
    {
    	$response['status'] =false;
        $response['status_code'] =$code;
        $response['errors'] = $errorMessages;
        return response()->json($response, $code);
    }
    
    public function exceptionMsg($exp){
        return "In file ".$exp->getFile." at line ".$exp->getLine." in code ".$exp->getCode." with message ".$exp->getMessage();
    }

    public function noContent () {
        return $this->sendError(trans('responses.msgs.no_content'), config('constant.header_code.no_content'));
    }
    
    public function getUser ($id=null) {                
        if($id){
            return \App\User::find($id);
        }
        return request()->user('api');
    }
    
}
