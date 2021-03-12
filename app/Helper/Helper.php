<?php
namespace App\Helper;
use Illuminate\Support\Facades\Config;

Class Helper{
 
    public static function getUserInfo ($id=null) {                
        if($id){
            return \App\User::find($id);
        }
        return request()->user();
    }
    
    public static function apiErrorResponse($errorMessages = [], $code = 404) {
        $response['status'] =false;
        $response['status_code'] =$code;
        $response['errors'] = $errorMessages;
        return response()->json($response, $code);
    }
    
    public static function isProjectAdmin () {
        $status =false;
        if(request()->user()->role == Config::get('constant.userrole.admin')){
            $status =true;
        }
        return $status;
    }
   
    public static function createJWTToken(){
        // Create token header as a JSON string
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

        // Create token payload as a JSON string
        $payload = json_encode([
            'iss' => env('TOKBOX_API_KEY'), 
            'ist'=>'project', 'iat'=> time(), 
            'exp'=> strtotime("+5 minutes"), 
            'jti'=> uniqid()
            ]);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, env('TOKBOX_APP_SECRET'), true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }
}
?>

