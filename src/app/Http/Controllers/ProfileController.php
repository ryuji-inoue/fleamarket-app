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

         // 仮ユーザー
        $user = User::first();
        //$user = auth()->user();

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
            'name' => 'required',
        ]);

        $user->name = $request->name;
        $user->save();

        return redirect()->route('mypage');
    }
}