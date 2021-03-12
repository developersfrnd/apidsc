<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxAgeValidation;

class ProfileRequest extends FormRequest
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
        $now = \Carbon\Carbon::now();
        return [
            'name' => 'sometimes|required|min:'.config('constant.minName').'|max:'.config('constant.maxName'),
            'username' => 'sometimes|required|min:'.config('constant.minUsername').'|max:'.config('constant.maxUsername').
            '|regex:/^\S*$/|regex:/^[\w-]*$/|unique:users,username',
            'country_code' => 'nullable|required_with:phone|numeric',
            'phone' => 'nullable|min:'.config('constant.minPhoneLength').'|max:'.config('constant.maxPhoneLength'),
            'birthdate' =>['sometimes','required:date','before_or_equal:'.$now->subYears(config('constant.minimumAge'))
            ->format(config('constant.app.date_format_without_time')),new MaxAgeValidation()],
            'cover_photo'=> 'nullable|image',
            'profile_picture'=> 'nullable|image',            
            'email' => 'sometimes|required|email|unique:users,email',
        ];
    }
    
    public function messages(){
        return [
            'birthdate.before_or_equal' => trans('responses.msgs.minimumAge'),
            'username.regex' => trans('responses.msgs.validUsername')
        ];
    }
}
