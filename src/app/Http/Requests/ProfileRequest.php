<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    // 認証済みユーザーのみ許可
    public function authorize()
    {
        return true; // 認証はミドルウェアで行うので true
    }

    // バリデーションルール
    public function rules()
    {
        return [
            'name' => 'required|max:20',

            'postal_code' => [
                'required',
                'regex:/^\d{3}-\d{4}$/'
            ],

            'address' => 'required|max:255',

            'building' => 'nullable|max:255',

            'profile_image' => 'nullable|mimes:jpeg,png',
        ];
    }

    // カスタムメッセージ
    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力して下さい。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力して下さい。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',

            'address.required' => '住所を入力して下さい。',

            'profile_image.mimes' => '画像はjpegまたはpng形式でアップロードしてください。',
        ];
    }
}