<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
Use App\Http\Resources\ProfilePictureResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image_base_path = config('constant.S3_URL') . config('constant.paths.AVATARS') . '/';        
        return [
          'about' => $this->profile->about, 
          'birthdate' => $this->profile->birthdate, 
           $this->merge(new ProfilePictureResource($this)),  
          'created_at' => $this->profile->created_at->toDateTimeString(), 
          'updated_at' => $this->profile->updated_at->toDateTimeString(), 
        ];
    }
}
