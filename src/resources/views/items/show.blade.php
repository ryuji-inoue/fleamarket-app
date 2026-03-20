@extends('layouts.app')

@section('title','商品詳細')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')

<div class="detail-container">

    {{-- 左側：画像 --}}
    <div class="detail-left">
        <img src="{{ asset('storage/'.$item->image_path) }}" alt="商品画像">
    </div>

    {{-- 右側：情報 --}}
    <div class="detail-right">

        <h2 class="item-title">{{ $item->name }}</h2>
        <p class="brand">{{ $item->brand }}</p>

        <p class="price">
            ¥{{ number_format($item->price) }}
            <span>(税込)</span>
        </p>

        {{-- いいね & コメント数 --}}
        <div class="meta-icons">

            <form action="{{ route('favorite.store', $item->id) }}" method="POST">
                @csrf
                <button class="heart-btn">
                    <img 
                        src="{{ $item->isFavoritedBy(auth()->id() ?? 0) 
                            ? asset('storage/images/heart-on.png') 
                            : asset('storage/images/heart-off.png') 
                        }}" 
                        alt="いいね"
                    >
                </button>
                <span>{{ $item->favorites->count() }}</span>
            </form>

            <div class="comment-icon">
                <img src="{{ asset('storage/images/comment.png') }}" alt="コメント">
                <span>{{ $item->comments->count() }}</span>
            </div>

        </div>

        {{-- 購入ボタン --}}
        @if($item->status == 1)
            <button class="btn-primary" disabled>売り切れ</button>
        @else
            <a href="/purchase/{{ $item->id }}" class="btn-primary">
                購入手続きへ
            </a>
        @endif

        {{-- 商品説明 --}}
        <div class="section">
            <h3>商品説明</h3>
            <p>{{ $item->description }}</p>
        </div>

        {{-- 商品情報 --}}
        <div class="section">
            <h3>商品の情報</h3>
           
            <p>カテゴリ：
                @if($item->categories->isEmpty())
                    <span>未選択</span>
                @else
                    @foreach($item->categories as $category)
                        <span class="category-tag">
                            {{ $category->name }}
                        </span>
                    @endforeach
                @endif
            </p>

            <p>
                商品の状態：
                <span class="condition-text">
                    {{ $item->condition->name ?? '未設定' }}
                </span>
            </p>
        </div>

        {{-- コメント一覧 --}}
        <div class="section">
            <h3>コメント ({{ $item->comments->count() }})</h3>

            @forelse($item->comments as $comment)
                <div class="comment-item">

                    <div class="comment-user">
                        <img 
                            src="{{ $comment->user->profile_image 
                                ? asset('storage/'.$comment->user->profile_image) 
                                : asset('storage/images/default-user.png') }}"
                            class="user-icon"
                            alt=""
                        >
                        <span class="user-name">{{ $comment->user->name }}</span>
                    </div>

                    <div class="comment-text">
                        {{ $comment->content }}
                    </div>

                </div>
            @empty
                <p class="no-comment">まだコメントはありません</p>
            @endforelse
        </div>

        {{-- コメント投稿 --}}
        @include('components.error')

        @auth
            <form action="{{ route('comments.store', $item->id) }}" method="POST" class="comment-form">
                @csrf
                <textarea name="content" placeholder="商品のコメント">{{ old('content') }}</textarea>

                <button type="submit" class="btn-primary">
                    コメントを送信する
                </button>
            </form>
        @endauth

    </div>

</div>

@endsection