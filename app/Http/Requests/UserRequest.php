<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
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
     $user_id= request()->input('user_id');
        $user = User::find($user_id);


     $rules=[
        'fname'=>['required'],
        'lname'=>['required'],
        'text_color'=>['required'],
        'background_color'=>['required'],


     ];

     if($user){
        $rules['email']=['required','unique:users,email,'.$user->id];

        $rules['password']=['required','confirmed','min:8'];

     }else{
        $rules['email']=['required'];
        if(request()->has('changePassword')){
        $rules['password']=['required','confirmed','min:8'];
        }

     }
        return $rules;





    }



    public function messages()
    {
        return [
            'fname.required' => 'فیلد نام الزامی است.',
            'lname.required' => 'فیلد نام خانوادگی الزامی است.',
            'email.required'=>'فیلد ایمیل الزامی است.',
            'password.required'=> 'فیلد پسورد الزامی است.',
            'password.min'=>'فیلد پسورد باید حداقل 8 کاراکتر داشته باشد.',
            'password.confirmed'=>'فیلد پسورد با تکرار پسورد مطابقت ندارد.'
        ];
    }
}
