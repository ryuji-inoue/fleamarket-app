<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // 商品名検索（部分一致）
        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // ログイン時、自分の商品を除外
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        // マイリスト
        if ($request->tab === 'mylist') {

            if (!auth()->check()) {
                $items = collect(); // 未認証は空
            } else {
                $items = auth()->user()
                    ->favoriteItems()
                    ->when($request->keyword, function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->keyword . '%');
                    })
                    ->get();
            }

        } else {
            $items = $query->latest()->get();
        }

        return view('items.index', compact('items'));
    }
}