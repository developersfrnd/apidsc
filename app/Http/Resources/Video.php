<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;


use Illuminate\Http\Resources\Json\JsonResource;

class Video extends JsonResource
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
            'name' => $this->when($this->isPurchased($this->id,$this->user_id), $this->name),
            'title' => $this->title,
            'creditPoints' => $this->creditPoints,
            'description' => $this->description,
            'privacy' => $this->privacy,
            'duration' => $this->duration,
            'thumb' => asset('storage/galleries/'.$this->thumb),
            'created_at' => $this->created_at->toDateTimeString(), 
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    private function isPurchased($video_id, $owner_id){
        $user = request()->user('api');
        if(!$user){
            return false;
        }else if($user->role == config('constant.userrole.model') && $owner_id == $user->id){
            return true;
        }
        else{
            return $user->purchasedVideos()->where('video_id',$video_id)->count();
        }
    }
}
