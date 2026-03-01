@extends('layouts.app')
@section('show-title', true)
@section('body-class', 'body--auth')

@section('title', 'ユーザ登録')

@section('content')
<div class="auth">
    <h2 class="auth__title">Register</h2>

    <div class="auth__card">
        <form class="form" action="/register" method="post">
            @csrf

            <div class="auth__group">
                <label class="auth__label">お名前</label>
                <input type="text" name="name" class="auth__input" value="{{ old('name') }}" placeholder="例: 山田　太郎" >
                @error('name')
                    <p class="msg--error">{{ $message }}</p>
                @enderror
            </div>

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
                <button type="submit" class="auth__button">登録</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/auth/auth.css') }}">
@endpush

@section('header-buttons')
    <a href="{{ route('login') }}" class="btn--primary">login</a>
@endsection