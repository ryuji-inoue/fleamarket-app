@extends('layouts.app')

@section('title','プロフィール設定')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endsection

@section('content')
<div class="edit-container">

    <h2 class="profile-title">プロフィール設定</h2>

    <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 画像 + ボタン --}}
        <div class="avatar-section">
            <div class="avatar">
                <img id="profilePreview"
                     src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : '' }}"
                     class="user-image">
            </div>

            <input
                type="file"
                id="profileImage"
                name="profile_image"
                class="js-image-input"
                data-preview="profilePreview"
                accept="image/*"
            >

            <label for="profileImage" class="image-btn">画像を選択する</label>

        </div>

        <div class="form-group">
            <label>ユーザー名</label>
            <input type="text" name="name" value="{{ old('name',$user->name) }}">
        </div>

        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" name="postal_code" value="{{ old('postal_code',$user->postal_code) }}">
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" name="address" value="{{ old('address',$user->address) }}">
        </div>

        <div class="form-group">
            <label>建物名</label>
            <input type="text" name="building" value="{{ old('building',$user->building) }}">
        </div>

        <button class="update-btn">更新する</button>

    </form>

</div>

@endsection