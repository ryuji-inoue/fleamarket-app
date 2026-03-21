<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required|max:255',
            'image' => 'required|mimes:jpeg,png',
            'category_id' => 'required',
            'condition_id' => 'required',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            // 商品名
            'name.required' => '商品名を入力してください',

            // 商品説明
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',

            // 商品画像
            'image.required' => '商品画像をアップロードしてください',
            'image.mimes' => '商品画像はjpegまたはpng形式でアップロードしてください',

            // カテゴリー
            'category_id.required' => '商品のカテゴリーを選択してください',

            // 状態
            'condition_id.required' => '商品の状態を選択してください',

            // 価格
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
        ];
    }
}