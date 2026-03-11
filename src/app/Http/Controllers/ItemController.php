<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Models\Category;
use App\Models\Condition;

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

    // 出品画面
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::orderBy('sort')->get();

        return view('items.sell', compact('categories'));
    }

    // 出品登録
    public function store(Request $request)
    {
        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $path,
            'condition' => $request->condition,
            'status' => 0
        ]);

        return redirect('/')->with('message','出品しました');
    }

    // 商品詳細
    public function show(Item $item)
    {
        $item->load(['comments.user', 'favorites']);

        return view('items.show', compact('item'));
    }

}