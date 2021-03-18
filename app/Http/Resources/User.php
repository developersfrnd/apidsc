<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GalleryResource as GalleryResource;
use App\Http\Resources\Video as VideoResource;
use App\Http\Resources\CategoryResource as CategoryResource;
use App\Http\Resources\EthnicityResource as EthnicityResource;
Use App\Http\Resources\LanguageResource as LanguageResource;
use Illuminate\Support\Facades\Storage;
use App\User as UserModel;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
          'id' => $this->id, 
          'creditPoints' => $this->creditPoints, 
          'role' => $this->role,
          'name' => $this->name,
          'is_online' => $this->is_online,
          'email' => $this->email,
          'phone' => $this->phone,
          'emailVerified' => $this->email_verified_at ? 1 : 0,
          'dob' => $this->dob,
          'age' => $this->getAge($this->dob),
          'gender' => $this->gender,
          'profilePicture'=> $this->when($this->profilePicture,asset('storage/galleries/'.$this->profilePicture),null),
          'is_liked' => $this->when(auth('api')->user(), $this->isModelLiked($this->id)),
          'address' => $this->address, 
          'city' => $this->city,
          'state' => $this->state,
          'country' => $this->country,
          'zipcode' => $this->zipcode,
          'categories' => $this->categories, 
          'languages' => $this->languages, 
          'languagesArr' => $this->when($this->languages,$this->languages()->pluck('language_id')->toArray(),[]),
          'categoriesArr' => $this->when($this->categories,$this->categories()->pluck('category_id')->toArray(),[]), 
          'body' => $this->body,
          'ethnicity' => $this->ethnicity,
          // 'categoryResource' => new CategoryResource($this->category),
          'speaking' => $this->speaking($this->languages()->pluck('language_id')->toArray()),
          'ethnicityResource' => new EthnicityResource($this->ethnicities),
          'weight' => $this->weight,
          'height' => $this->height, 
          'hairColor' => $this->hairColor,
          'hairLength' => $this->hairLength,
          'eyeColor' => $this->eyeColor,
          'orientation' => $this->orientation,
          'isProfilePublished' => $this->isProfilePublished,
          'status'=> $this->status,
          'charges'=> $this->cherges,
          'created_at' => $this->created_at->toDateTimeString(), 
          'updated_at' => $this->updated_at->toDateTimeString(), 
        ];
    }

    private function getAge($userDob) {
      $dob = new \DateTime($userDob);
      $now = new \DateTime();
      $difference = $now->diff($dob);
      return $difference->y;
    }
    
    private function speaking($languageArr) {
      if($languageArr){
        return LanguageResource::collection(\App\Language::whereIn('id',$languageArr)->get());
      }else{
        return null;
      }  
    }

    private function isModelLiked($model_id){
      if(auth('api')->user() && auth('api')->user()->role == config('constant.userrole.customer')){
        return auth('api')->user()->userLikesTo()->where('model_id', $model_id)->count();
      }else{
          return false;
      }
    }
}
