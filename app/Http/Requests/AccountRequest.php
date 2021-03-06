<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_name' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required|numeric',
            'ifsc_code' => 'required',
            'charge_per_minute' => 'required|numeric'
        ];
    }
}
