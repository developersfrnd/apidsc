<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpenTokResource extends JsonResource
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
            'channelSessionId' => $this->channelSessionId,
            'channelToken' => $this->channelToken
        ];

    }
}
