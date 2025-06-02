<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'ship_date' => 'required',
            'ship_name' => 'required',
            'ship_zip' => 'required',
            'ship_pref_id' => 'required',
            'ship_city' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力して下さい。',
            'email.required' => 'メールアドレスを入力して下さい。',
            'email.email' => 'メールアドレスを正しく入力して下さい。',
            'ship_date.required' => 'お届け希望日を入力して下さい。',
            'ship_name.required' => 'お名前を入力して下さい。',
            'ship_zip.required' => '発送先郵便番号を入力して下さい。',
            'ship_pref_id.required' => '発送先都道府県を選択して下さい。',
            'ship_city.required' => '発送先市区町村を入力して下さい。',
        ];
    }

    protected function failedValidation($validator)
    {
        if (request()->expectsJson()) {
            $response['errors']  = $validator->errors()->toArray();

            throw new HttpResponseException(
                response()->json($response, 422)
            );
        } else {
            parent::failedValidation($validator);
        }
    }

}
