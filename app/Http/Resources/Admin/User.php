<?php

namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\GalleryResource as GalleryResource;
use App\Http\Resources\Video as VideoResource;
use App\Http\Resources\CategoryResource as CategoryResource;
use App\Http\Resources\EthnicityResource as EthnicityResource;
Use App\Http\Resources\LanguageResource as LanguageResource;

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
          'role' => $this->role,
          'name' => $this->name,
          'email' => $this->email,
          'phone' => $this->phone,
          'emailVerified' => $this->email_verified_at ? 1 : 0,
          'dob' => $this->dob,
          'age' => $this->getAge($this->dob),
          'profilePicture'=> $this->when($this->profilePicture,asset('storage/galleries/'.$this->profilePicture),null),
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
          'galleries' => GalleryResource::collection($this->galleries),
          'isProfilePublished' => $this->isProfilePublished,
          'status'=> $this->status,
          'videos' => VideoResource::collection($this->videos), 
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
}
