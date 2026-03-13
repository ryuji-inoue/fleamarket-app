<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Payment;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    // 購入画面
    public function create(Request $request,$item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $payments = Payment::all();

            $selectedPayment = null;

        if ($request->has('payment_method')) {
            $selectedPayment = Payment::find($request->payment_method);
        }

        return view('purchase.create', compact(
            'item',
            'user',
            'payments',
            'selectedPayment'
        ));
    }

    public function store(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $user = auth()->user();

        $address = session('purchase_address');

        // 売却済みチェック
        if ($item->status == 1) {
            return redirect('/')
                ->with('error','この商品はすでに購入されています');
        }

        // ① 購入保存
        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_id' => $request->payment_id,
            'postal_code' => $address['postal_code'] ?? $user->postal_code,
            'address' => $address['address'] ?? $user->address,
            'building' => $address['building'] ?? $user->building,
        ]);

        // ② 商品をsoldにする
        $item->status = 1;
        $item->save();

        // ③ 商品一覧へ
        return redirect('/');
    }

    // 住所変更画面
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('purchase.edit', compact('item','user'));
    }

    // 住所更新
    public function updateAddress(Request $request, $item_id)
    {
        // バリデーション
        $request->validate([
            'postal_code' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        // sessionに保存
        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        ]);

        // 商品購入画面に戻る
        return redirect()->route('purchase.create', $item_id);
    }

}