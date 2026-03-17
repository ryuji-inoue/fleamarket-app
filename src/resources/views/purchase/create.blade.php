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

            <img src="{{ asset('storage/'.$item->image_path) }}"class="item-image" alt="商品画像">

            <div class="item-info">
                <h2>{{ $item->name }}</h2>
                <p class="price">¥{{ number_format($item->price) }}</p>
            </div>

        </div>

        {{-- 支払い方法 --}}
        <div class="payment-section">

            <h3>支払い方法</h3>

            <form action="{{ route('purchase.create', $item->id) }}" method="GET">
                <select name="payment_method" onchange="this.form.submit()">
                    <option value="" disabled selected>選択してください</option>
                    @foreach($payments as $payment)
                        <option value="{{ $payment->id }}"
                            {{ $paymentId == $payment->id ? 'selected' : '' }}>
                            {{ $payment->name }}
                        </option>
                    @endforeach
                </select>
            </form>

        </div>

        {{-- 配送先 --}}
        <div class="address-section">

            <div class="address-header">
                <h3>配送先</h3>
                <a href="{{ route('address.edit', $item->id) }}">変更する</a>
            </div>

            {{-- 初期値はユーザー住所、住所変更時はsessionから住所を取得 --}}
            <p>
                〒 {{ $address['postal_code'] ?? $user->postal_code }}
                {{ $address['address'] ?? $user->address }}
                {{ $address['building'] ?? $user->building }}
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
                    <span>
                       <span>{{ $selectedPayment->name ?? '未選択' }}</span>
                    </span>
            </div>

        </div>
        
    <form action="{{ route('purchase.store', $item->id) }}" method="POST">
        @csrf

        <input type="hidden" name="payment_id" value="{{ $paymentId }}">
        <input type="hidden" name="postal_code" value="{{ $address['postal_code'] }}">
        <input type="hidden" name="address" value="{{ $address['address'] }}">
        <input type="hidden" name="building" value="{{ $address['building'] }}">

        <button class="purchase-btn">
            購入する
        </button>

        @include('components.error')
    </form>

    
    <form action="{{ route('purchase.stripe',$item->id) }}" method="POST">
        @csrf

        <input type="hidden" name="payment_id" value="{{ $paymentId }}">
        <input type="hidden" name="postal_code" value="{{ $address['postal_code'] }}">
        <input type="hidden" name="address" value="{{ $address['address'] }}">
        <input type="hidden" name="building" value="{{ $address['building'] }}">
        
        <button type="submit">
        Stripeで支払う
        </button>
    </form>

    </div>

</div>

@endsection