<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    // 商品一覧（トップ）
    public function index(Request $request)
    {
        if ($request->tab === 'mylist' && auth()->check()) {
            $items = auth()->user()->favoriteItems()->latest()->get();
        } else {
            $items = Item::latest()->get();
        }

        return view('items.index', compact('items'));
    }

    // 商品詳細
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('items.show', compact('item'));
    }
}