@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/address.css') }}">
@endsection

@section('content')

<div class="address-container">

    <h2>住所の変更</h2>

    <form action="/purchase/address/{{ $item->id }}" method="POST">
        @csrf

        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" name="postal_code">
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address">
        </div>

        <div class="form-group">
            <label>建物名</label>
            <input type="text" name="building">
        </div>

        <button class="update-btn">更新する</button>

    </form>

</div>

@endsection