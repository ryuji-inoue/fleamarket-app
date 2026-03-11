<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        Favorite::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id
        ]);

        return back(); // 元のページに戻る
    }
}
