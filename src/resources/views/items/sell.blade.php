@extends('layouts.app')

@section('title','商品出品')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection

@section('content')

<div class="sell-container">

    <h2 class="page-title">商品の出品</h2>
        @include('components.error')
        
    <form action="/sell" method="POST" enctype="multipart/form-data" class="sell-form">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-group">
            <label>商品画像</label>

            <div class="image-upload">
                <img id="itemPreview" class="user-image" src="">

                <input
                    type="file"
                    id="image"
                    name="image"
                    class="js-image-input"
                    data-preview="itemPreview"
                >

                <label for="image" class="image-btn">画像を選択する</label>
            </div>
        </div>

        {{-- 商品の詳細 --}}
        <h3 class="section-title">商品の詳細</h3>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-list">
                @foreach ($categories as $category)
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
            <select name="condition_id" class="form-control" required>
                <option value="">選択してください</option>
                @foreach ($conditions as $condition)
                    <option value="{{ $condition->id }}"
                        {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                        {{ $condition->name }}
                    </option>
                @endforeach
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

        <button type="submit" class="submit-btn">出品する</button>

    </form>
</div>

@endsection