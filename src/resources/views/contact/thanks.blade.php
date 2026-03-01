@extends('layouts.app')
@section('title', 'サンクスページ')

@push('css')
<link rel="stylesheet" href="{{ asset('css/contact/thanks.css') }}">
@endpush

@section('content')

<div class="thanks">

    <div class="thanks__inner">
        <p class="thanks__message">
            お問い合わせありがとうございました
        </p>

        <a href="/" class="thanks__button">
            HOME
        </a>
    </div>

</div>

@endsection