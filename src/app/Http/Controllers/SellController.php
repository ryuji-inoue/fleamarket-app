<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class SellController extends Controller
{
    // 出品画面
    public function create()
    {
        return view('sell.create');
    }

    // 出品登録
    public function store(Request $request)
    {



        $path = null;

        if ($request->hasFile('image')) {

            $path = $request->file('image')->store('items', 'public');

        }

        Item::create([
            'user_id' => 1,
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $path,
            'condition' => $request->condition,
            'status' => 0
        ]);

        return redirect('/')->with('message','出品しました');
    }
}