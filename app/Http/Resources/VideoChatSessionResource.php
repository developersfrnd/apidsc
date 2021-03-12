<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoChatSessionResource extends JsonResource
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
            'token' => $this->pricePerCredit,
            'channel' => $this->minCreditPurchase,
            'subscriber_id' => $this->subscriber_id,
            'subscriber_token' => $this->subscriber_token,
            'created_at' => $this->created_at
        ];
    }
    
}
