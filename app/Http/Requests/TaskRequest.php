<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'title' => 'required',
            'duration' => 'required',


        ];
    }


    public function messages(){
        return [
            'title.required'=>'عنوان الزامی می باشد.',
            'description.required'=>'توضیحات الزامی می باشد.',
            'duration.required'=>'مدت زمان انجام الزامی می باشد.'
        ];
    }
}
