<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;

class Auth extends JsonResource
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
            $this->merge(new UserResource($this)),
            'expires_at' => $this->expires_at,
        ];
    }
}
