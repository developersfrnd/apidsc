<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'pricePerCredit' => $this->pricePerCredit,
            'minCreditPurchase' => $this->minCreditPurchase,
            'adminCommissionPercent' => $this->adminCommissionPercent
        ];
    }
}
