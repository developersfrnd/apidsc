<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user' => $this->user()->select(['id','name','email'])->first(),
            'credits' => $this->credits,
            'pricePerCredit' => $this->pricePerCredit,
            'amount' => $this->amount,
            'transction_id' => $this->transction_id, 
            'created_at' => substr($this->created_at->toDateTimeString(),0,10), 
            'updated_at' => substr($this->updated_at->toDateTimeString(),0,10), 
          ];
    }
}
