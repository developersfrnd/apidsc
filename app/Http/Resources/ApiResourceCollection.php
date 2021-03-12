<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class ApiResourceCollection extends ResourceCollection
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

//    public function toResponse($request)
//    {
//        return $this->resource instanceof AbstractPaginator
//                    ? (new PaginatedResource($this))->toResponse($request)
//                    : parent::toResponse($request);
//    }

    public function with($request){
        return [
            'status'=> true,
            'status_code'=> config('constant.header_code.ok'),
            'message' => trans('responses.msgs.success'),
        ];
    }
}