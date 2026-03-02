@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')

<div class="purchase-container">

    <div class="left">
        <div class="item-summary">
            <img src="{{ $item->image_url }}">
            <div>
                <p>{{ $item->name }}</p>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <hr>

        <div>
            <label>支払い方法</label>
            <select name="payment_method">
                <option>コンビニ払い</option>
                <option>クレジットカード</option>
            </select>
        </div>

        <hr>

        <div>
            <label>配送先</label>
            <p>{{ auth()->user()->address }}</p>
            <a href="/purchase/address/{{ $item->id }}">変更する</a>
        </div>
    </div>

    <div class="right">
        <div class="summary-box">
            <p>商品代金 ¥{{ number_format($item->price) }}</p>
            <button class="buy-btn">購入する</button>
        </div>
    </div>

</div>

@endsection