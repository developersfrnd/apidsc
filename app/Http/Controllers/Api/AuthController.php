<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Resources\Auth as AuthResource;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\UserSignupRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Mail\ContactUs;
use DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use App\Mail\UserSignup;
use App\Mail\ResendCode;
use App\Mail\ForgotPasswordToken;
use Illuminate\Support\Facades\Mail;

class AuthController extends APIController {
    
    public function login(AuthLoginRequest $request) {
        
         $credentials = [
            'username' => request()->username,
            'password' => request()->password,
        ];
        $user = User::where('username',$request->username)->first();
        
        if (empty($user) || !Hash::check($request->password, $user->password)) {
            return $this->sendError(trans('responses.msgs.invalidUser'), config('constant.header_code.validaion_fail'));
        } else if($user->status == config('constant.user.deactivate.key')) {
            return $this->sendError(trans('responses.msgs.user_block_byadmin'), config('constant.header_code.unauthorize'));
        } else {
           Auth::attempt($credentials);
        }
        $user = $this->userToken($request->user());
        return $this->sendResponse(new AuthResource($user), trans('responses.msgs.logedIn'), config('constant.header_code.ok'));
    }

    public function signup(UserSignupRequest $request) {
        DB::beginTransaction();
        $user = new User;
        try{
            $verification_code = random_int(100000, 999999);
            $user->username = $request->username;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->role = $request->role;
            $user->dob = substr($request->dob,0,10);
            $user->password = bcrypt($request->password);
            $user->remember_token = $verification_code;
            $user->save();
            DB::commit();

            Mail::to($user->email)->send(new UserSignup($user));
            
            $user = $this->userToken($user);

            return $this->sendResponse(new AuthResource($user), trans('responses.msgs.success'), config('constant.header_code.ok'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError($e->getMessage(), config('constant.header_code.exception'));
        }
    }
    
    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return $this->sendResponse('',trans('responses.msgs.success'),config('constant.header_code.ok'));
    }

    public function user(Request $request) {
        return $this->sendResponse(new UserResource($request->user()),trans('responses.msgs.success'));
    }

    public function isEmailAvalaible(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        try{
            $user = User::where('email', '=', $request->email)->first();
            if ($user) {
                return $this->sendResponse($user->email,trans('responses.msgs.emailAlreadyExist'), config('constant.header_code.ok'));
            }
            return $this->sendResponse('', trans('responses.msgs.success'), config('constant.header_code.ok'));
        } catch (\Exception $e) {           
            return $this->sendError($e->getMessage(), config('constant.header_code.exception'));
        }
    }

    public function forgotpasswordtoken(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        try{
            $error_message = 'Email not exist';
            $user = User::where('email', '=', $request->email)->first();
            if ($user) {
                $forgot_password_token = random_int(100000, 999999);
                $user->forgot_password_token = $forgot_password_token;
                $user->save();
                Mail::to($user->email)->send(new ForgotPasswordToken($user));
                return $this->sendResponse($user,trans('responses.msgs.success'), config('constant.header_code.ok'));
            }
            return $this->sendError($error_message, config('constant.header_code.validaion_fail'));
        } catch (\Exception $e) {           
            return $this->sendError($e->getMessage(), config('constant.header_code.exception'));
        }
    }

    public function setPassword(Request $request) {
        $validator = $request->validate([
            'forgot_password_token' => 'required',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ]);
        try {
            $error_message = 'Wrong Token';
            $user = User::where('email', '=', $request->email)->where('forgot_password_token',$request->forgot_password_token)->first();
            if ($user) {
                $user->password = bcrypt($request->password);
                $user->forgot_password_token = null;
                $user->save();
                return $this->sendResponse('',trans('responses.msgs.success'), config('constant.header_code.ok'));
            }
            return $this->sendError($error_message, config('constant.header_code.validaion_fail'));
        } catch (\Exception $e) {           
            return $this->sendError($e->getMessage(), config('constant.header_code.exception'));
        }        
    }

    public function verify(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        try{
            $user = User::where('email', '=', $request->email)->first();
            $error_message = 'Email not exist';     
            if ($user) {
                if(!is_null($user->email_verified_at)){
                    $error_message = 'Already verified please login';
                }else if($user->remember_token != $request->remember_token){
                    $error_message = 'Wrong verification code';
                }else{
                    $user->email_verified_at = date("Y-m-d H:i:s");
                    $user->save();
                    $user = $this->userToken($user);
                    return $this->sendResponse(new AuthResource($user),trans('responses.msgs.success'), config('constant.header_code.ok'));
                }
            }            
            return $this->sendError($error_message, config('constant.header_code.validaion_fail'));
        } catch (\Exception $e) {           
            return $this->sendError($e->getMessage(), config('constant.header_code.exception'));
        }
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        try{
            $user = User::where('email', '=', $request->email)->first();
            $error_message = 'Email not exist'; 
            if ($user) {
                if(!is_null($user->email_verified_at)){
                    $error_message = 'Already verified please login';
                }else{                    
                    $verification_code = random_int(100000, 999999);
                    $user->remember_token = $verification_code;
                    $user->save();
                    Mail::to($user->email)->send(new ResendCode($user));   
                    return $this->sendResponse('',trans('responses.msgs.code_send'), config('constant.header_code.ok'));
                }
            }
            return $this->sendError($error_message, config('constant.header_code.validaion_fail'));
        } catch (\Exception $e) {           
            return $this->sendError($e->getMessage(), config('constant.header_code.exception'));
        }
    }

    public function changePassword(Request $request) {

        $validator = $request->validate([
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ], (new User)->messages());
        
        $user = User::find($request->user()->id);
        $user->password = bcrypt($request->password);
        $user->save();
        
        return $this->sendResponse(new UserResource($request->user()),trans('responses.msgs.success'));
    }
    
    public function contactUs(Request $request) {
        Mail::to(Config::get('constant.ADMIN_MAIL'))->send(new ContactUs($request));
        return $this->sendResponse(Config::get('constant.ADMIN_MAIL'), trans('responses.contactus'), config('constant.header_code.ok'));
    }

    /* For get default config data*/
    public function config()
    {
        $data['privacy'] = config('constant.privacy');
        $data['comments'] = config('constant.comments');
        $data['license'] = config('constant.license');
        $data['setting'] = config('constant.privacy');
        return $this->sendResponse($data, trans('responses.msgs.success'), config('constant.header_code.ok'));
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    private function userToken($user) {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save(); 
        $user->token = $tokenResult->accessToken;        
        $user->expires_at = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(); 
        return $user;
    }

}
