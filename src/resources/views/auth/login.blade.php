@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')

<div class="auth-container">

    <h2 class="page-title">ログイン</h2>
    @include('components.error')

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>メールアドレス</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>パスワード</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="main-btn">
            ログインする
        </button>

        <div class="register-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>

    </form>

</div>

@endsection