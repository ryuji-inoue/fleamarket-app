<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            // 1. お名前
            'last_name'  => 'required|string|max:8',
            'first_name' => 'required|string|max:8',

            // 2. 性別
            'gender' => 'required',

            // 3. メールアドレス
            'email' => 'required|email',

            // 4. 電話番号（3分割）
            'tel1' => ['required','regex:/^[0-9]+$/','max:5'],
            'tel2' => ['required','regex:/^[0-9]+$/','max:5'],
            'tel3' => ['required','regex:/^[0-9]+$/','max:5'],

            // 5. 住所
            'address' => 'required',

            // 6. 建物名（任意）
            'building' => 'nullable',

            // 7. お問い合わせの種類
            'contact_type' => 'required',

            // 8. お問い合わせ内容
            'detail' => 'required|string|max:120',
            ];
    }


   public function messages()
    {
        return [
             // 1. お名前
            'last_name.required'  => '姓を入力してください',
            'first_name.required' => '名を入力してください',

            'last_name.max'  => '姓は 8文字以下にしてください。',
            'first_name.max' => '名は 8文字以下にしてください。',
            
            // 2. 性別
            'gender.required' => '性別を選択してください',

            // 3. メールアドレス
            'email.required' => 'メールアドレスを入力してください',
            'email.email'    => 'メールアドレスはメール形式で入力してください',

            // 4. 電話番号
            'tel1.required' => '電話番号を入力してください',
            'tel2.required' => '電話番号を入力してください',
            'tel3.required' => '電話番号を入力してください',

            'tel1.regex' => '電話番号は 半角英数字で入力してください',
            'tel2.regex' => '電話番号は 半角英数字で入力してください',
            'tel3.regex' => '電話番号は 半角英数字で入力してください',

            'tel1.max' => '電話番号は 5桁まで数字で入力してください',
            'tel2.max' => '電話番号は 5桁まで数字で入力してください',
            'tel3.max' => '電話番号は 5桁まで数字で入力してください',

            // 5. 住所
            'address.required' => '住所を入力してください',

            // 7. お問い合わせの種類
            'contact_type.required' => 'お問い合わせの種類を選択してください',

            // 8. お問い合わせ内容
            'detail.required' => 'お問い合わせ内容を入力してください',
            'detail.max'      => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }    
}
