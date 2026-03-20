@extends('layouts.app')

@section('title','会員登録')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')

<div class="auth-container">

    <h2>会員登録</h2>
    @include('components.error')

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label>ユーザー名</label>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password">
        </div>

        <div class="form-group">
            <label>確認用パスワード</label>
            <input type="password" name="password_confirmation">
        </div>

        <button class="main-btn">登録する</button>

        <div class="login-link">
            <a href="/login">ログインはこちら</a>
        </div>

    </form>

</div>

@endsection