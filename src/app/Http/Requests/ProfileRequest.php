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
            'name' => 'required|max:255',
            'postal_code' => 'nullable|max:8',
            'address' => 'nullable|max:255',
            'building' => 'nullable|max:255',
            'profile_image' => 'nullable|image',
        ];
    }

    // カスタムメッセージ（任意）
    public function messages()
    {
        return [
            'name.required' => '名前は必須です。',
            'profile_image.image' => 'プロフィール画像は画像ファイルを指定してください。',
        ];
    }
}