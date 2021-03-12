<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfilePictureResource extends JsonResource {

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        $image_base_path = config('constant.S3_URL') . config('constant.paths.AVATARS') . '/';
        $thumbnail_base_path = config('constant.S3_URL') . config('constant.paths.THUMBS') . '/';

        return [
            'profile_picture' => ($this->profile && $this->profile->profile_picture) ? $image_base_path . $this->profile->profile_picture : null,
            'profile_picture_thumbnail' => ($this->profile && $this->profile->profile_picture) ? $thumbnail_base_path . $this->profile->profile_picture : null,
            'cover_photo' => ($this->profile && $this->profile->cover_photo) ? $image_base_path . $this->profile->cover_photo : null,
        ];
    }

}
