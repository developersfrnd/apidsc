<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Admin\AdminsController;
use Illuminate\Support\Facades\Auth;
use \App\Http\Resources\Admin\AdminAuth;
use Carbon\Carbon;
use App\Http\Resources\Admin\AdminResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class AuthController extends AdminsController {

    public function login(Request $request) {
        
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        $credentials = [
            'email' => request()->email,
            'password' => request()->password,
            'role' => Config::get('constant.userrole.admin'),
            
        ];
        
        if (!Auth::attempt($credentials))
            return $this->sendError(trans('responses.msgs.invalidUser'), config('constant.header_code.validaion_fail'));
        
        $user = $this->userToken($request->user());
        return $this->sendResponse(new AdminAuth($user), trans('responses.msgs.logedIn'), config('constant.header_code.ok'));
    }

    public function logout(Request $request) {          
        $this->loggedInUser()->token()->revoke();
        return $this->sendResponse('', trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    public function user(Request $request) {
        return $this->sendResponse(new AdminResource($this->loggedInUser()), trans('responses.msgs.success'));
    }

    public function changePassword(Request $request) {

        $validator = $request->validate([
            'old_password' => 'required|min:5',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ]);

        if (!Hash::check($request->input('old_password'), $this->loggedInUser()->password)) {
            return $this->sendError(__('responses.msgs.user_password_mismatch_error'), config('constant.header_code.validaion_fail'));
        }        
        $this->loggedInUser()->password = bcrypt($request->password);
        $this->loggedInUser()->save();

        return $this->sendResponse(new AdminResource($this->loggedInUser()), trans('responses.msgs.password_change'));
    }

    private function userToken($user) {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        $user->token = $tokenResult->accessToken;
        $user->expires_at = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();
        return $user;
    }
}
