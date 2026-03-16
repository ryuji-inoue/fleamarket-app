@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/show.css') }}">
@endsection

@section('content')

<div class="profile-container">

    {{-- 上部プロフィール情報 --}}
    <div class="profile-header">
        <div class="profile-left">
            <div class="avatar">
                <img src="{{ asset('storage/'.(auth()->user()->profile_image ?? 'user/default-user.png')) }}" class="user-image" alt="プロフィール画像">
            </div>
            <h2>{{ auth()->user()->name ?? 'ゲストユーザー' }}</h2>
        </div>

        <a href="/mypage/profile" class="edit-btn">プロフィールを編集</a>
    </div>

    {{-- タブ --}}
    <div class="tab-menu">
        <a href="/mypage?page=sell" class="{{ request('page') !== 'buy' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="/mypage?page=buy" class="{{ request('page') === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>

    {{-- 商品一覧 --}}
    <div class="item-grid">
        @foreach($items as $item)
            <div class="item-card">
                <div class="item-image">
                    <img src="{{ asset('storage/'.$item->image_path) }}" class="item-img" alt="商品画像">
                    @if($item->status === 1)
                        <span class="sold-label">sold</span>
                    @endif
                </div>
                <p>{{ $item->name }}</p>
                <p class="price">¥{{ number_format($item->price) }}</p>
            </div>
        @endforeach
    </div>

</div>

@endsection