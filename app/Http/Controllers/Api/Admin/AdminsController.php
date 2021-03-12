<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminsController extends Controller
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
    
    protected function loggedInUser(){
        return request()->user();
    }
}
