<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\User;

class ProfileController extends Controller
{
    /* プロフィール画面 */
    public function show(Request $request)
    {

        //ユーザー
        $user = auth()->user();

        // 出品商品
        if ($request->page === 'sell') {
            $items = Item::where('user_id', $user->id)->latest()->get();
        }
        // 購入商品
        else if ($request->page === 'buy') {
            $items = $user->purchases()->with('item')->latest()->get();
        }
        else {
            $items = collect();
        }

        return view('profile.show', compact('user', 'items'));
    }

    /* プロフィール編集画面 */
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    /*  プロフィール更新*/
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|max:255',
            'postal_code' => 'nullable|max:8',
            'address' => 'nullable|max:255',
            'building' => 'nullable|max:255',
            'profile_image' => 'nullable|image'
        ]);

        //画像保存
        if($request->hasFile('profile_image')){

            $path = $request->file('profile_image')->store('user','public');

            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;

        $user->save();

        return redirect()->route('mypage');
    }
}