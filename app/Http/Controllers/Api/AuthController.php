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
use DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class AuthController extends APIController {
    
    public function login(AuthLoginRequest $request) {
        
         $credentials = [
            'email' => request()->email,
            'password' => request()->password,
        ];
        $user = User::where('email',$request->email)->first();
        
        if (empty($user) || !Hash::check($request->password, $user->password)) {
            return $this->sendError(trans('responses.msgs.invalidUser'), config('constant.header_code.validaion_fail'));
        } else if($user->status == config('constant.user.deactivate.key')) {
            return $this->sendError(trans('responses.msgs.user_auth_unverified'), config('constant.header_code.unauthorize'));
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
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->role = $request->role;
            $user->dob = substr($request->dob,0,10);
            $user->password = bcrypt($request->password);
            $user->save();
            DB::commit();

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
        
        $request->validate([
            'subject' => 'required|min:5',
            'description' => 'required|max:'.config('constant.description_max_length'),
        ]);          
        $options = ['username' => ($this->getUser())?ucwords($this->getUser()->username):'', 'useremail' => ($this->getUser())?$this->getUser()->email:'', 'to'=>Config::get('constant.ADMIN_MAIL'), 'subject'=>'Contact Us', 'usersubject'=>$request->subject, 'usermessage'=>$request->description]; 
        EmailService::sendMail('contactUs', $options);
        return $this->sendResponse('', trans('responses.contactus'), config('constant.header_code.ok'));
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        try{
            $user = User::where('email', '=', $request->email)->first();
            if ($user->hasVerifiedEmail()) {
                return $this->sendError(trans('responses.msgs.emailAlreadyVerified'), config('constant.header_code.forbidden'));
            }
            $user->sendEmailVerificationNotification();
            return $this->sendResponse(new UserResource($user), trans('responses.msgs.verificationEmail'), config('constant.header_code.ok'));
        } catch (\Exception $e) {           
            return $this->sendError($e->getMessage(), config('constant.header_code.exception'));
        }
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
