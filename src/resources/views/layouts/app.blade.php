<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FleaMarket')</title>

    {{-- レスポンシブ  --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- 共通CSS  --}}
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">

    {{-- ページ別CSS  --}}
    @yield('css')
</head>
<body>

@php
    $isAuthPage = request()->routeIs(['login', 'register', 'verification.notice']);
@endphp

<header class="header">
    <div class="logo">
        <a href="{{ url('/') }}"
            @if($isAuthPage)
                onclick="event.preventDefault();"
            @endif
        >
            <img src="{{ asset('storage/images/logo.png') }}" alt="ロゴ">
        </a>
    </div>

    {{-- ログイン・会員登録時は非表示 --}}
    @if (!$isAuthPage)

        <form method="GET" action="{{ request()->routeIs('items.mylist') ? route('items.mylist') : route('items.index') }}">
            <input type="text" name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="なにをお探しですか？">
        </form>

        <div class="nav">
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                ログアウト
            </a>

            <a href="{{ route('mypage') }}">マイページ</a>

            <a href="{{ route('items.sell') }}" class="nav-sell-btn">
                出品
            </a>
        </div>

    @endif

        <form id="logout-form"
                action="{{ route('logout') }}"
                method="POST"
                style="display:none;">
            @csrf
        </form>
    </div>
</header>

<div class="container">
    @yield('content')
</div>
</body>
<script src="{{ asset('js/image-preview.js') }}"></script>

</html>

