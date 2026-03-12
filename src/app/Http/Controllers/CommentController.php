<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Item;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Item $item)
    {
        // バリデーション
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // コメント作成
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'content' => $request->content,
        ]);

        // 元の画面にリダイレクト
        return redirect()->back()->with('success', 'コメントを投稿しました');
    }
}
