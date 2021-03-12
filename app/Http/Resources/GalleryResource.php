<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
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
            'name' => asset('storage/galleries/'.$this->name),
            'title' => $this->title,
            'tag' => $this->tag,
            'description' => $this->description,
            'privacy' => $this->privacy,
            'mediaType' => $this->mediaType,
            'created_at' => $this->created_at->toDateTimeString(), 
            'updated_at' => $this->updated_at->toDateTimeString(), 
          ];
    }
}
