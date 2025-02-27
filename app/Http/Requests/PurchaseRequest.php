<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'ship_name' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力して下さい。',
            'email.required' => 'メールアドレスを入力して下さい。',
            'email.email' => 'メールアドレスを正しく入力して下さい。',
            'ship_name.required' => 'お名前を入力して下さい。',
        ];
    }
}
