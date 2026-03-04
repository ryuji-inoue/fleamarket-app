@extends('layouts.app')

@section('content')

<div class="container">

    {{-- 🔍 検索フォーム --}}
    <form method="GET" action="{{ route('items.index') }}" class="search-form">
        <input type="hidden" name="tab" value="{{ request('tab') }}">
        <input type="text" name="keyword" placeholder="なにをお探しですか？"
               value="{{ request('keyword') }}">
        <button type="submit">検索</button>
    </form>

    {{-- タブ --}}
    <div class="tabs">
        <a href="{{ route('items.index', ['tab' => null, 'keyword' => request('keyword')]) }}"
           class="{{ request('tab') !== 'mylist' ? 'active' : '' }}">
            おすすめ
        </a>

        <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}"
           class="{{ request('tab') === 'mylist' ? 'active' : '' }}">
            マイリスト
        </a>
    </div>

    {{-- 商品一覧 --}}
    <div class="item-grid">
        @forelse($items as $item)
            <div class="item-card">
                <div class="image-wrapper">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="">
                    @if($item->is_sold)
                        <span class="sold-label">Sold</span>
                    @endif
                </div>
                <p class="item-name">{{ $item->name }}</p>
            </div>
        @empty
            <p class="empty">商品がありません</p>
        @endforelse
    </div>

</div>

@endsection