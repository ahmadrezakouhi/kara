<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SprintRequest extends FormRequest
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
            'title'=>'required',
            'description'=>'required',
            'duration'=>'required',
            'start_date'=>'required',
            'end_date'=>'required'
        ];
    }



    public function messages()
    {
        return [
            'title.required'=>'عنوان الزامی می باشد.',
            'description.required'=>'توضیحات الزامی می باشد.',
            'duration.required'=>'مدت زمان انجام الزامی می باشد .',
            'start_date.required'=>'تاریخ شروع الزامی می باشد.',
            'end_date.required'=>'تاریخ پایان الزامی می باشد.'
        ];
    }
}
