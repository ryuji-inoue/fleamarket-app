@extends('layouts.app')

@section('title','プロフィール')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/show.css') }}">
@endsection

@section('content')

@php $user = auth()->user(); @endphp

<div class="profile-container">

    {{-- 上部プロフィール情報 --}}
    <div class="profile-header">
        <div class="profile-left">
            <div class="avatar">
                <img 
                    src="{{ $user->profile_image 
                        ? asset('storage/'.$user->profile_image) 
                        : asset('images/default.png') }}"
                    class="user-image"
                    alt="プロフィール画像"
                >
            </div>
            <h2>{{ $user->name ?? 'ゲストユーザー' }}</h2>
        </div>

        <a href="{{ route('mypage.profile') }}" class="edit-btn">プロフィールを編集</a>
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
                    <img 
                        src="{{ $item->image_path 
                            ? asset('storage/'.$item->image_path) 
                            : asset('images/no-image.png') }}"
                        class="item-img"
                        alt="商品画像"
                    >
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