<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'FashionablyLate')</title>

        <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
        @stack('css')
    </head>

    <body class="@yield('body-class')">

        <header class="wrapper__header">
            @hasSection('show-title')
                <h1 class="wrapper__title">FashionablyLate</h1>
            @endif

            <div class="wrapper__buttons">
                @yield('header-buttons')
            </div>
        </header>

        <div class="wrapper">
            <main class="wrapper__content">
                @yield('content')
            </main>
        </div>

    </body>
</html>