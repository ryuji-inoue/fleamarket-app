<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileRequest;

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
        $soldItems = Item::where('user_id', $user->id)->latest()->get();

        // 購入商品
        $boughtItems = Item::whereIn('id', function ($query) use ($user) {
            $query->select('item_id')
                  ->from('purchases')
                  ->where('user_id', $user->id);
        })->latest()->get();

        // 表示するタブに応じて items を切り替え
        if ($request->page === 'buy') {
            $items = $boughtItems;
        } else {
            $items = $soldItems; // 初期表示は出品商品
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
    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        // 画像アップロード時
        if ($request->hasFile('profile_image')) {

            // 既存画像があれば削除
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // 新しい画像を保存
            $path = $request->file('profile_image')->store('user', 'public');

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