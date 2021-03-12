<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminAuth extends JsonResource
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
            'token' => $this->token,            
            'token_type' => 'Bearer',
            'expires_at' => $this->expires_at,
            'name' => $this->name,
        ];
    }
}
