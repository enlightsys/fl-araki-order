<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'kind1' => 'required_without:kind2',
            'name' => 'required',
            'email' => 'required|email:rfc',
            'message' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'kind1.required_without' => 'お問い合わせの種類を選択してください。',
            'name.required' => 'お名前を入力して下さい。',
            'email.required' => 'メールアドレスを入力して下さい。',
            'email.email' => 'メールアドレスを正しく入力して下さい。',
            'message.required' => 'お問い合わせ内容を入力して下さい。',
        ];
    }
}
