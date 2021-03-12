<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $image_base_path = config('constant.S3_URL') . config('constant.paths.CATEGORY') . '/';
        $thumbnail_base_path = config('constant.S3_URL') . config('constant.paths.THUMBS') . '/';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status
        ];
    }
}
