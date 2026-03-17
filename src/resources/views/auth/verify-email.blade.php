@extends('layouts.app')

@section('title','メール認証')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')

<div class="verify-container">

    <p class="verify-text">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <div class="verify-button">
        <a href="/edit" class="verify-btn">
            認証はこちらから
        </a>
    </div>

    <div class="resend">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="resend-link">
                認証メールを再送する
            </button>
        </form>
    </div>

</div>

@endsection