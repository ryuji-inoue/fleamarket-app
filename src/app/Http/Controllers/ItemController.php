<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // FN014 商品一覧
    public function index(Request $request)
    {
        $query = Item::query();

        // 自分の商品を除外（ログイン時）
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // 商品名検索（部分一致）
        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => 'recommend',
            'keyword' => $request->keyword
        ]);
    }

    // FN015 マイリスト
    public function mylist(Request $request)
    {
        if (!Auth::check()) {
            return view('items.index', [
                'items' => collect(),
                'tab' => 'mylist',
                'keyword' => $request->keyword
            ]);
        }

        $likedIds = Like::where('user_id', Auth::id())
                        ->pluck('item_id');

        $query = Item::whereIn('id', $likedIds);

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => 'mylist',
            'keyword' => $request->keyword
        ]);
    }

    public function sell()
    {
        $categories = Category::all();

        return view('items.sell', compact('categories'));
    }

}