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
            'zip' => 'required|max:8',
            'pref_id' => 'required',
            'city' => 'required|max:255',
            'tel' => 'required|max:13',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => "お名前を入力して下さい。",
            'zip.required' => "郵便番号を入力してください。",
            'pref_id.required' => "都道府県を選択してください。",
            'city.required' => "市区町村を入力してください。",
            'tel.required' => "電話番号を入力してください。",
        ];
    }
}
