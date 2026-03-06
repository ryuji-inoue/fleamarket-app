@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection

@section('content')

<div class="sell-container">

    <h2 class="page-title">商品の出品</h2>

    <form action="/sell" method="POST" enctype="multipart/form-data" class="sell-form">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-group">
            <label>商品画像</label>
            <div class="image-upload">
                <input type="file" name="image" id="image">
                <label for="image" class="image-btn">画像を選択する</label>
            </div>
        </div>

        {{-- 商品の詳細 --}}
        <h3 class="section-title">商品の詳細</h3>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-list">
                @foreach($categories as $category) -->
                    <label class="category-tag">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 商品の状態 --}}
        <div class="form-group">
            <label>商品の状態</label>
            <select name="condition">
                <option value="">選択してください</option>
                <option value="1">新品・未使用</option>
                <option value="2">未使用に近い</option>
                <option value="3">目立った傷や汚れなし</option>
                <option value="4">やや傷や汚れあり</option>
                <option value="5">傷や汚れあり</option>
                <option value="6">全体的に状態が悪い</option>
            </select>
        </div>

        {{-- 商品名 --}}
        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="name">
        </div>

        {{-- ブランド名 --}}
        <div class="form-group">
            <label>ブランド名</label>
            <input type="text" name="brand">
        </div>

        {{-- 商品説明 --}}
        <div class="form-group">
            <label>商品の説明</label>
            <textarea name="description"></textarea>
        </div>

        {{-- 販売価格 --}}
        <div class="form-group">
            <label>販売価格</label>
            <input type="number" name="price" placeholder="¥">
        </div>

        <button type="submit" class="sell-btn">出品する</button>

    </form>
</div>

@endsection