<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力して下さい。',
        ];
    }
}
