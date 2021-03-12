<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Admin\VideoResource;

class VideoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => VideoResource::collection($this->collection)
        ];
    }
}
