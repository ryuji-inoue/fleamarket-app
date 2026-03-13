<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\CommentRequest;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {

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
