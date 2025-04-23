<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Member;

class ProfilePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'current_password' => ['required', function ($attribute, $value, $fail) {
                $member_id = Auth::user()->id;
                $member = Member::find($member_id);
                if (!\Hash::check($value, $member->password)) {
                    $fail('パスワードが一致しません。');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'current_password.required' => "パスワードを入力してください。",
            'password.required' => "パスワードを入力してください。",
            'password.confirmed' => "パスワードが確認用の値と一致しません。",
        ];
    }
}
