<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;
use \App\Http\Resources\ApiResourceCollection;

class UserCollection extends ApiResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
