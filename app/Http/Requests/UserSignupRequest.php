<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSignupRequest extends FormRequest
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
    
    public function messages(){
        return [
            'password.confirmed' => trans('responses.msgs.passwordConfirmed'),
            'password.regex' => trans('responses.msgs.validPassword')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'name' => 'required|min:'.config('constant.minName').'|max:'.config('constant.maxName'),
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:5|confirmed',
        'password_confirmation' => 'required'
        ];
    }
}
