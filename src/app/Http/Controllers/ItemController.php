<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Category;
use App\Models\Condition;

use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品一覧
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

    // マイリスト
    public function mylist(Request $request)
    {
        if (!Auth::check()) {
            return view('items.index', [
                'items' => collect(),
                'tab' => 'mylist',
                'keyword' => $request->keyword
            ]);
        }

        $favoriteIds = Favorite::where('user_id', Auth::id())
                        ->pluck('item_id');

        $query = Item::whereIn('id', $favoriteIds);

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

        return view('items.sell', compact('categories'), compact('conditions'));
    }

    // 出品登録
    public function store(Request $request)
    {
        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        $item = Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'image_path' => $path,
            'condition_id' => $request->condition_id,
            'status' => 0
        ]);

        // カテゴリ複数保存
        if($request->categories){
            $item->categories()->attach($request->categories);
        }

        return redirect('/')->with('message','出品しました');
    }

    // 商品詳細
    public function show(Item $item)
    {
        $item->load(['comments.user', 'favorites']);

        return view('items.show', compact('item'));
    }

}