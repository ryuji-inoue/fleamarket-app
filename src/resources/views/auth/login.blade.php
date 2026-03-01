@extends('layouts.app')
@section('show-title', true)

@section('title', 'ログイン')

@section('content')
<div class="auth">
    <h2 class="auth__title">Login</h2>

    <div class="auth__card">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth__group">
                <label class="auth__label">メールアドレス</label>
                <input type="email" name="email" class="auth__input"  value="{{ old('email') }}" placeholder="例: test@example.com">
                @error('email')
                    <p class="msg--error">{{ $message }}</p>
                @enderror
            </div>

            <div class="auth__group">
                <label class="auth__label">パスワード</label>
                <input type="password" name="password" class="auth__input" placeholder="例: coachtech1106">
                @error('password')
                    <p class="msg--error">{{ $message }}</p>
                @enderror                
            </div>

            <div class="auth__button-area">
                <button type="submit" class="auth__button">ログイン</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@section('header-buttons')
    <a href="{{ route('register') }}" class="btn--primary">register</a>
@endsection