@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/item.css') }}">
@endsection

@section('content')

<div class="tabs">
    <a href="{{ route('items.index', ['keyword' => $keyword]) }}"
       class="{{ $tab === 'recommend' ? 'active' : '' }}">
        おすすめ
    </a>

    <a href="{{ route('items.mylist', ['keyword' => $keyword]) }}"
       class="{{ $tab === 'mylist' ? 'active' : '' }}">
        マイリスト
    </a>
</div>

<div class="item-grid">
    @forelse($items as $item)
        <div class="item-card">
            <div class="image-wrapper">
                <img src="{{ asset('storage/' . $item->image) }}">
                @if($item->is_sold)
                    <span class="sold">Sold</span>
                @endif
            </div>
            <p class="item-name">{{ $item->name }}</p>
        </div>
    @empty
        <p>商品がありません</p>
    @endforelse
</div>

@endsection