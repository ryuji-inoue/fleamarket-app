<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        $user = auth()->user();

        // 既にお気に入りしているか
       $favorite = $user->favorites()->where('item_id', $item_id)->first();

        if ($favorite) {
            // 解除
            $favorite->delete();
        } else {
            // 登録
            $user->favorites()->create([
                'user_id' => $user->id,
                'item_id' => $item_id
            ]);
        }

        return back(); // 元のページに戻る
    }
}
