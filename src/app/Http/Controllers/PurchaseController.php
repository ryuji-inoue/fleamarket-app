<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    // ---------------------
    // 商品購入関連
    // ---------------------

    /**
     * 購入画面
     */
    public function create(Request $request, Item $item)
    {
        $user = auth()->user();
        $payments = Payment::all();

        $selectedPayment = null;

        // 支払い方法
        if ($request->payment_method) {
            session(['payment_method' => $request->payment_method]);
        }

        $paymentId = session('payment_method');

          $selectedPayment = $paymentId
        ? Payment::find($paymentId)
        : null;

        if ($paymentId) {
            $selectedPayment = Payment::find($paymentId);
        }

        // 住所情報はセッション優先、なければユーザー情報
        $address = session('purchase_address') ?? [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building
        ];

        return view('purchase.create', compact(
            'item',
            'payments',
            'selectedPayment',
            'paymentId',
            'address',
            'user'
        ));
    }

    /**
     * Stripe決済
     */
    public function stripeCheckout(PurchaseRequest $request, Item $item)
    {
        // セッションに住所と支払情報を保存
        session([
            'purchase_data' => [
                'payment_id' => session('payment_method'),
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        ]);

        // local環境だけスキップ
        if (app()->environment('local')) {
            return redirect()->route('purchase.success', ['item' => $item->id]);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'], 
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            // 成功・キャンセルURL
            'success_url' => route('purchase.success', ['item' => $item->id]),
            'cancel_url' => route('purchase.create', ['item' => $item->id]),
        ]);

        return redirect($session->url);
    }

    /**
     * 決済成功
     */
    public function success(Item $item)
    {
        $user = auth()->user();
        $purchase = session('purchase_data');

        // 二重購入防止
        if ($item->status == 1) {
            return redirect('/');
        }

        // purchases テーブルに保存
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_id' => $purchase['payment_id'] ?? 1,
            'postal_code' => $purchase['postal_code'],
            'address' => $purchase['address'],
            'building' => $purchase['building'],
        ]);

        // 商品ステータス更新
        $item->update(['status' => 1]);

        // セッションクリア
        session()->forget('purchase_data');
        session()->forget('purchase_address');

        return redirect('/'); // 購入完了画面にリダイレクト可能
    }

    /**
     * 住所変更画面
     */
    public function editAddress(Item $item)
    {
        $user = auth()->user();

        $address = session('purchase_address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);

        return view('purchase.edit', compact('item', 'address', 'user'));
    }

    /**
     * 住所更新
     */
    public function updateAddress(AddressRequest $request, Item $item) 
    {

        // セッションに保存
        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        ]);

        return redirect()->route('purchase.create', ['item' => $item->id]);
    }
}