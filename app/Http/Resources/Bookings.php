<?php

namespace App\Http\Resources;

use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\BookingResource;

class Bookings extends ApiResourceCollection
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
            'data' => BookingResource::collection($this->collection)
        ];
    }
}
