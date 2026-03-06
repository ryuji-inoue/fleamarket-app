<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FleaMarket')</title>

    <!-- 共通CSS -->
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">

    <!-- ページ別CSS -->
    @yield('css')
</head>
<body>

<header class="header">
    <div class="logo">COACHTECH</div>

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

        <a href="{{ route('items.sell') }}" class="sell-btn">
            出品
        </a>

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
</html>