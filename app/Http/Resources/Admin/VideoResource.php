<?php

namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'name' => asset('storage/'.$this->name),
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'thumb' => asset('storage/galleries/'.$this->thumb), 
            'created_at' => $this->created_at->toDateTimeString(), 
            'updated_at' => $this->updated_at->toDateTimeString(), 
          ];
    }
}
