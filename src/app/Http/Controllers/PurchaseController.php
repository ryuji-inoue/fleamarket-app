<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    // 購入画面
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $payments = Payment::all();

        // 支払い方法
        $selectedPayment = null;
        $paymentId = $request->payment_method;

        if ($paymentId) {
            $selectedPayment = Payment::find($paymentId);
        }

        // 配送先（session優先）
        $address = session('purchase_address');

        if (!$address) {
            $address = [
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building
            ];
        }


        return view('purchase.create', compact(
            'item',
            'payments',
            'selectedPayment',
            'paymentId',
            'address'
        ));
    }

    // 購入処理
    public function store(PurchaseRequest $request, $item_id)
    {

        dd($request->all());
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        // 購入済みチェック
        if (Purchase::where('item_id', $item->id)->exists()) {
            return back()->withErrors(['item' => 'この商品はすでに購入されています']);
        }

        // session住所取得
        $address = session('purchase_address');

        if (!$address) {
            $address = [
                'postal_code' => $user->postal_code,
                'address' => $user->address,
                'building' => $user->building
            ];
        }

        $paymentId = $request->payment_id ?? 1;

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_id' => $request->payment_id,
            'postal_code' => $address['postal_code'],
            'address' => $address['address'],
            'building' => $address['building'],
        ]);

        // 売り切れ
        $item->update([
            'status' => 1
        ]);

        // session削除
        session()->forget('purchase_address');

        return redirect('/');
    }

    // 住所変更画面
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $address = session('purchase_address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchase.edit', compact('item', 'address', 'user'));
    }

    // 住所更新
    public function updateAddress(Request $request, $item_id)
    {

        $request->validate([
            'postal_code' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();

        session([
        'purchase_address' => [
            'postal_code'=>$request->postal_code,
            'address'=>$request->address,
            'building'=>$request->building
        ]
        ]);

        return redirect()->route('purchase.create', $item_id);
    }
}