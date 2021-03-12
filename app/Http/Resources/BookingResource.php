<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ScheduleResource;


class BookingResource extends JsonResource
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
            'schedule' => new ScheduleResource($this->schedule()->select(['id','fromTime','toTime','channelSessionId'])->first()),
            'model' => $this->model()->select(['id','name','profilePicture'])->first(),
            'createdAt' => $this->created_at
        ];
    }

}
