<?php

namespace App\Http\Controllers\Api;

use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use \App\Http\Resources\User as UserResource;
use App\Traits\Images;
use App\Http\Requests\ProfileRequest;

class ProfilesController extends APIController {
    
    use Images;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request) {
        
        $user = request()->user();
        $checkEmail = $profile_picture = false;
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('country_code')) {
            $user->country_code = $request->country_code;
        }
        if (($request->has('email')) && empty($user->email)) {
            $checkEmail = true;
            $user->email = $request->email;
        }
        $profile = Profile::firstOrCreate(['user_id' => request()->user()->id]);
        if ($request->has('about')) {
            $profile->about = $request->about;
        }
        if ($request->has('birthdate')) {
            $profile->birthdate = $request->birthdate;
        }
        if ($request->hasFile('profile_picture')) {
            
            $this->deleteImage($profile->profile_picture,config('constant.paths.AVATARS'));
            $profile_picture_name = $this->getUser()->username.'.'.$request->file('profile_picture')->getClientOriginalExtension();            
            $saved_file_name = $this->storeImage($request->file('profile_picture'),config('constant.paths.AVATARS'),true);
            $profile->profile_picture = $saved_file_name;
            $profile_picture =true;
            $image_path = config('constant.S3_URL') . config('constant.paths.AVATARS') . '/'. $saved_file_name;
            $options = ['image_url' => $image_path];
        }
        if ($request->hasFile('cover_photo')) {
            
            $this->deleteImage($profile->cover_photo,config('constant.paths.AVATARS'));
            $saved_cover_photo_name = $this->storeImage($request->file('cover_photo'),config('constant.paths.AVATARS'));
            $profile->cover_photo = $saved_cover_photo_name;
        }
        
        $profile->save();
        $user->save();
        
        $user->updateMatrixCredentials(false, $profile_picture);
        
        if ($checkEmail && is_null($user->email_verified_at)) {
            $user->sendEmailVerificationNotification();
        }
        
        return $this->sendResponse(new UserResource($user), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile) {
        //
    }

    /*
      Remove if old profile picture exists
     */

    public function removeProfilePicture() {
        
        if (request()->user()->profile) {            
            $profile = request()->user()->profile;
            $profile_picture = $profile->profile_picture;
            $profile->profile_picture = null;
            $profile->save();            
            $this->deleteImage($profile_picture,config('constant.paths.AVATARS'));
            $options = ['matrix_user_id'=> request()->user()->matrix_user_id, 'matrix_token'=> request()->user()->matrix_token];
            $this->setAvatarUrl(null,$options);
            
        }

        return $this->sendResponse(new UserResource(\App\User::find(request()->user()->id)), trans('responses.msgs.deleteProfilePicture'), config('constant.header_code.ok'));
    }

    /*
      Remove if old cover picture exists
     */

    public function removeCoverPhoto() {
        if (request()->user()->profile) {        
            $profile = request()->user()->profile;
            $cover_photo = $profile->cover_photo;
            $profile->cover_photo = null;
            $profile->save();            
            $this->deleteImage($cover_photo,config('constant.paths.AVATARS'));
        }
        return $this->sendResponse(new UserResource(\App\User::find(request()->user()->id)), trans('responses.msgs.deleteCoverPhoto'), config('constant.header_code.ok'));
    }
}
