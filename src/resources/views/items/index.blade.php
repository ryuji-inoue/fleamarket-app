@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')

<div class="tabs">
    <a href="/" class="tab {{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
    <a href="/?tab=mylist" class="tab {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<div class="items-container">
    @foreach($items as $item)
        <a href="/item/{{ $item->id }}" class="item-card">
            <img src="{{ $item->image_url }}" alt="商品画像">
            <p class="item-name">{{ $item->name }}</p>
        </a>
    @endforeach
</div>

@endsection