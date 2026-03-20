<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Purchase;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    // 購入画面
    public function create(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $payments = Payment::all();

        $selectedPayment = null;
        $paymentId = $request->payment_method;

        if ($paymentId) {
            $selectedPayment = Payment::find($paymentId);
        }

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
            'address'
        ));
    }

    // Stripe決済
    public function stripeCheckout(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // セッションに保存
        session([
            'purchase_data' => [
                'payment_id' => $request->payment_id,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'], // ←一本化

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

            'success_url' => route('purchase.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchase.create', ['item' => $item->id]),
        ]);

        return redirect($session->url);
    }

    // 決済成功
    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        $purchase = session('purchase_data');

        // 二重購入防止（重要）
        if ($item->status == 1) {
            return redirect('/');
        }

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_id' => $purchase['payment_id'] ?? 1,
            'postal_code' => $purchase['postal_code'],
            'address' => $purchase['address'],
            'building' => $purchase['building'],
        ]);

        $item->update([
            'status' => 1
        ]);

        session()->forget('purchase_data');

        return redirect('/');
    }

    // 住所変更
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

    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:8',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        ]);

        return redirect()->route('purchase.create', $item_id);
    }
}