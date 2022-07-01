<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSections extends FormRequest
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

            'section_name_Ar' => 'required',
            'section_name_En' => 'required',
            'grade_id' => 'required',
            'Class_id' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'section_name_Ar.required' => trans('Sections_trans.required_ar'),
            'section_name_En.required' => trans('Sections_trans.required_en'),
            'grade_id.required' => trans('Sections_trans.grade_id_required'),
            'Class_id.required' => trans('Sections_trans.Class_id_required'),
        ];
    }
}
