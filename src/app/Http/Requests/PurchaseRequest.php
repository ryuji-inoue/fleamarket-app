<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Purchase;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_id' => 'required|integer|exists:payments,id',
        ];
    }

    public function messages()
    {
        return [
            'payment_id.required' => '支払い方法を選択してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $itemId = $this->route('item_id');

            if (Purchase::where('item_id', $itemId)->exists()) {
                $validator->errors()->add('item', 'この商品はすでに購入されています');
            }

        });
    }

}
