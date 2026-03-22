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
            <label for="postal_code">郵便番号</label>
            <input 
                id="postal_code"
                type="text" 
                name="postal_code" 
                value="{{ old('postal_code', $address['postal_code'] ?? '') }}"
                class="@error('postal_code') is-invalid @enderror"
            >
        </div>

        {{-- 住所 --}}
        <div class="form-group">
            <label for="address">住所</label>
            <input 
                id="address"
                type="text" 
                name="address" 
                value="{{ old('address', $address['address'] ?? '') }}"
                class="@error('address') is-invalid @enderror"
            >
        </div>

        {{-- 建物名 --}}
        <div class="form-group">
            <label for="building">建物名</label>
            <input 
                id="building"
                type="text" 
                name="building" 
                value="{{ old('building', $address['building'] ?? '') }}"
                class="@error('building') is-invalid @enderror"
            >
        </div>

        <button type="submit" class="update-btn">更新する</button>
        
    </form>

</div>

@endsection