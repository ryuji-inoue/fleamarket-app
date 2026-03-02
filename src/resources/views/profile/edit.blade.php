@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/edit.css') }}">
@endsection

@section('content')

<div class="edit-container">

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
            <input type="text" name="postal_code" value="{{ auth()->user()->postal_code }}">
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address" value="{{ auth()->user()->address }}">
        </div>

        <div class="form-group">
            <label>建物名</label>
            <input type="text" name="building" value="{{ auth()->user()->building }}">
        </div>

        <button class="update-btn">更新する</button>

    </form>

</div>

@endsection