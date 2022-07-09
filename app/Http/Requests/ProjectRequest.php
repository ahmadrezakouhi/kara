<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'title'=>['required'],
            'description'=>['required'],
            'start_date'=>['required'],
            'end_date'=>['required']
        ];
    }


    public function messages(){
        return [
            'title.required'=>'فیلد عنوان الزامی می باشد.',
            'description.required'=>'فیلد توضیحات الزامی می باشد.',
            'start_date.required'=>'فیلد زمان شروع الزامی می باشد.',
            'end_date.required'=>'فیلد زمان پایان الزامی می باشد.'
        ];
    }
}
