@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/create.css') }}">
@endsection

@section('content')

<div class="purchase-wrapper">

    {{-- 左側 --}}
    <div class="purchase-left">

        {{-- 商品情報 --}}
        <div class="item-section">

            <img src="{{ $item->image_url }}" class="item-image">

            <div class="item-info">
                <h2>{{ $item->name }}</h2>
                <p class="price">¥{{ number_format($item->price) }}</p>
            </div>

        </div>

        <hr>

        {{-- 支払い方法 --}}
        <div class="payment-section">

            <h3>支払い方法</h3>

            <select>
                <option>選択してください</option>
                <option>コンビニ払い</option>
                <option>カード払い</option>
            </select>

        </div>

        <hr>

        {{-- 配送先 --}}
        <div class="address-section">

            <div class="address-header">
                <h3>配送先</h3>
                <a href="#">変更する</a>
            </div>

            <p>
                〒 XXX-YYYY <br>
                ここには住所が入ります
            </p>

        </div>

    </div>

    {{-- 右側 --}}
    <div class="purchase-right">

        <div class="summary-box">

            <div class="summary-row">
                <span>商品代金</span>
                <span>¥{{ number_format($item->price) }}</span>
            </div>

            <div class="summary-row">
                <span>支払い方法</span>
                <span>コンビニ払い</span>
            </div>

        </div>

        <button class="purchase-btn">
            購入する
        </button>

    </div>

</div>

@endsection