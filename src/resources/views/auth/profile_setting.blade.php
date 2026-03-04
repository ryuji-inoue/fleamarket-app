@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/profile_setting.css') }}">
@endsection

@section('content')

<div class="profile-container">

    <h2>プロフィール設定</h2>

    <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="avatar-section">
            <div class="avatar"></div>
            <input type="file" name="image">
        </div>

        <div class="form-group">
            <label>ユーザー名</label>
            <input type="text" name="name" value="{{ auth()->user()->name }}">
        </div>

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

        <button class="main-btn">更新する</button>

    </form>

</div>

@endsection