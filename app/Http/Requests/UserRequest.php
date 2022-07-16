<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fname' => ['required'],
            'lname' => ['required'],
            'email' => ['required'],
            'mobile' => ['required'],
            'phone' => ['required'],
            'role' => ['required'],
            'password' => ['required']
        ];
    }



    public function messages()
    {
        return [
            'fname.required' => 'فیلد نام الزامی است.',
            'lname.required' => 'فیلد نام خانوادگی الزامی است.',
        ];
    }
}
