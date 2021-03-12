<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
Use App\Http\Resources\ProfilePictureResource;

class CommentResource extends JsonResource
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
            'comments' => $this->comments,
            'user_id' => $this->user_id, 
            'model_id' => $this->model_id,
            'rating' => $this->rating,
            'created_at' => $this->created_at->toDateTimeString(), 
            'updated_at' => $this->updated_at->toDateTimeString(), 
          ];
    }
}
