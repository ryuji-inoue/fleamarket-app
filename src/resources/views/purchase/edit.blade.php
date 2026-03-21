@extends('layouts.app')

@section('title','住所変更')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/edit.css') }}">
@endsection

@section('content')

<div class="address-container">

    <h2>住所の変更</h2>
    @include('components.error')

    <form action="{{ route('purchase.updateAddress', $item->id) }}" method="POST">
        @csrf

        {{-- 郵便番号 --}}
        <div class="form-group">
            <label>郵便番号</label>
            <input 
                type="text" 
                name="postal_code" 
                value="{{ old('postal_code', $address['postal_code']) }}"
            >
        </div>

        {{-- 住所 --}}
        <div class="form-group">
            <label>住所</label>
            <input 
                type="text" 
                name="address" 
                value="{{ old('address', $address['address']) }}"
            >
        </div>

        {{-- 建物名 --}}
        <div class="form-group">
            <label>建物名</label>
            <input 
                type="text" 
                name="building" 
                value="{{ old('building', $address['building']) }}"
            >
        </div>

        <button class="update-btn">更新する</button>
        
    </form>

</div>

@endsection