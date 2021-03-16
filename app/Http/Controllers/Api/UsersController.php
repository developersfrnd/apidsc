<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Category;
use App\Language;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\APIController;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Hash;
use App\Traits\Images;
use \App\Http\Resources\Videos;
use App\Helper\Helper;
use Illuminate\Support\Str;

class UsersController extends APIController
{
    use Images;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('role',config('constant.userrole.model'));
        if(request()->query('category')){
            $users->where('categories', 'like', '%'.request()->query('category').'%');
        };

        if(request()->query('ethnicity')){
            $users->where('ethnicity',request()->query('ethnicity'));
        };

        if(request()->query('orientation')){
            $users->where('orientation',request()->query('orientation'));
        };

        if(request()->query('language')){
            $users->where('languages', 'like', '%'.request()->query('language').'%');
        }
        
        if(request()->query('sort')){
            $users->orderBy(request()->query('sort'), 'desc');
        }else{
            $users->orderBy('is_online', 'desc')->orderBy('id','desc');
        }
         
        $users = $users->paginate(config('constant.pagination.per_page'));
        return new UserCollection($users);
    }

    /**
    * Get user detail by username
    */
    public function getByUsername($username = null) {        
        return $this->sendResponse(new UserResource(User::where('username', $username)->firstOrFail()), config('constant.msgs.success'), config('constant.header_code.ok'));
    }

    public function purchaseVideo(Request $request){
        $video = \App\Video::find($request->video_id);
        if($request->user()->creditPoints >= $video->creditPoints && !$request()->user()->purchasedVideos()->where('video_id',$request->video_id)->count()){

            $request->user()->purchasedVideos()->attach($request->video_id, ['creditPoints' => $video->creditPoints]);
            $request->user()->creditPoints = $request->user()->creditPoints - $video->creditPoints;
            $request->user()->save();
            return $this->sendResponse([], config('constant.msgs.success'), config('constant.header_code.ok'));
        
        }else{
            return $this->sendError(trans('responses.msgs.notEnoughCreditPoint'),config('constant.header_code.ok'));    
        }
        
    }

    public function videos(Request $request){
        $user = $request->user();
        if($user->role == config('constant.userrole.model')){
            return new Videos($user->videos()->orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
        }else{
            return new Videos($user->purchasedVideos()->orderBy('id','desc')->paginate(config('constant.pagination.per_page')));
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->sendResponse(new UserResource($user), trans('responses.msgs.success'), config('constant.header_code.ok'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $user = User::find($id);
        
        //Basec Information
        if ($request->has('name')) { $user->name = $request->name; }
        if ($request->has('dob')) { $user->dob = new \Carbon\Carbon($request->dob); }
        if ($request->has('gender')) { $user->gender = $request->gender; }
        if ($request->has('password')) { $user->password = bcrypt($request->zipcode); }
        

        //Personal Information
        if ($request->has('address')) { $user->address = $request->address; }
        if ($request->has('city')) { $user->city = $request->city; }
        if ($request->has('state')) { $user->state = $request->state; }
        if ($request->has('zipcode')) { $user->zipcode = $request->zipcode; }
        if ($request->has('phone')) { $user->phone = $request->phone; }
        if ($request->has('country')) { $user->country = $request->country; }
        
        
        //Questionnire
        $categoriesArr = $languageArr = [];
        if ($request->has('categories')) { 

            $categoriesArr = explode(',',$request->categories);
            $categories = Category::whereIn('id',$categoriesArr)->pluck('name')->toArray();
            $user->categories = implode(',',$categories); 
        }

        if ($request->has('languages')) { 

            $languageArr = explode(',',$request->languages);
            $languages = Language::whereIn('id',$languageArr)->pluck('name')->toArray();
            $user->languages = implode(',',$languages); 
        }

        if ($request->has('body')) { $user->body = $request->body; }
        if ($request->has('ethnicity')) { $user->ethnicity = $request->ethnicity; }
        if ($request->has('weight')) { $user->weight = $request->weight; }
        if ($request->has('height')) { $user->height = $request->height; }
        if ($request->has('hairColor')) { $user->hairColor = $request->hairColor; }
        if ($request->has('hairLength')) {  $user->hairLength = $request->hairLength; }
        if ($request->has('eyeColor')) {  $user->eyeColor = $request->eyeColor; }
        if ($request->has('orientation')) {  $user->orientation = $request->orientation; }
        
        if ($request->hasFile('profile_picture')) {
            
            $profile_picture_name = time().'.'.$request->file('profile_picture')->getClientOriginalExtension();            
            $saved_file_name = $this->storeImage($request->file('profile_picture'),config('constant.paths.AVATARS'),true);
            $user->profilePicture = $saved_file_name;
        }

        $user->save();

        if ($request->has('languages')) {  $user->languages()->sync($languageArr); }
        if ($request->has('categories')) {  $user->categories()->sync($categoriesArr); }

        return $this->sendResponse(new UserResource($user), config('constant.msgs.success'), config('constant.header_code.ok'));
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /**
     * Set user online
     * @return \Illuminate\Http\Response
     */
    public function onlineuser(Request $request){
        $request->user()->room = Str::uuid()->toString();
        $request->user()->is_online = 1;
        $request->user()->save();
        return $this->sendResponse(new UserResource($request->user()),trans('responses.msgs.success'), config('constant.header_code.ok'));
        
    }

    /**
     * Set user offline
     * @param $id user id
     * @return \Illuminate\Http\Response
     */
    public function offlineuser(Request $request){
        $request->user()->room = null;
        $request->user()->is_online = 0;
        $request->user()->save();
        return $this->sendResponse([],trans('responses.msgs.success'), config('constant.header_code.ok'));        
    }


    /**
     * checkusercoin
     * @param $request request
     * @return \Illuminate\Http\Response
     */
    public function checkusercoin(Request $request){
        $model_data = User::find($request->room_id);
        if ($model_data->cherges > $request->user()->creditPoints){
            return $this->sendResponse([],trans('responses.msgs.not_sufficient'), config('constant.header_code.HTTP_BAD_REQUEST'));
        }
        return $this->sendResponse([],trans('responses.msgs.success'), config('constant.header_code.ok'));                
    }

    /**
     * reduceusercoin
     * @param $request request
     * @return \Illuminate\Http\Response
     */
    public function reduceusercoin(Request $request){
        $model_data = User::find($request->room_id);
        if ($model_data->cherges > $request->user()->creditPoints){
            return $this->sendResponse([],trans('responses.msgs.not_sufficient'), config('constant.header_code.HTTP_BAD_REQUEST'));
        }
        $request->user()->creditPoints = ($request->user()->creditPoints - ((int)$model_data->cherges));
        $request->user()->save();
        return $this->sendResponse([],trans('responses.msgs.success'), config('constant.header_code.ok'));                
    }


}
