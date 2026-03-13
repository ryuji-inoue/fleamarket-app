@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/edit.css') }}">
@endsection

@section('content')

<div class="address-container">

    <h2>住所の変更</h2>

    <form action="{{ route('purchase.updateAddress', $item->id) }}" method="POST">
        @csrf

        {{-- 郵便番号 --}}
        <div class="form-group">
            <label>郵便番号</label>
            <input 
                type="text" 
                name="postal_code" 
                value="{{ old('postal_code', optional($user)->postal_code) }}"
            >
            @error('postal_code')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 住所 --}}
        <div class="form-group">
            <label>住所</label>
            <input 
                type="text" 
                name="address" 
                value="{{ old('address', optional($user)->address) }}"
            >
            @error('address')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 建物名 --}}
        <div class="form-group">
            <label>建物名</label>
            <input 
                type="text" 
                name="building" 
                value="{{ old('building', optional($user)->building) }}"
            >
            @error('building')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button class="update-btn">更新する</button>

    </form>

</div>

@endsection