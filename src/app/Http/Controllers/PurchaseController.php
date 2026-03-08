<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    // 購入画面
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchase.create', compact('item'));
    }

    // 購入処理
    public function store(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // TODO: 購入処理ロジック（決済・購入テーブル保存）

        return redirect('/mypage?page=buy');
    }

    // 住所変更画面
    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchase.address', compact('item'));
    }

    // 住所更新
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required',
            'address' => 'required',
        ]);

        // TODO: ユーザー住所更新処理

        return redirect("/purchase/{$item_id}");
    }
}