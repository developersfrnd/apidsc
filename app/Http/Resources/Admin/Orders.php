<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\ApiResourceCollection;
use App\Http\Resources\Admin\OrderResource;

class Orders extends ApiResourceCollection
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
            'data' => OrderResource::collection($this->collection)
        ];
    }
}
