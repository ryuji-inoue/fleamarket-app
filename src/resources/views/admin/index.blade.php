@extends('layouts.app')
@section('show-title', true)
@section('title', '管理画面')

@section('content')
<div class="admin">
    <h2 class="admin__title">Admin</h2>

    <!-- 検索フォーム -->
    <div class="admin__search">
        <form method="GET" action="{{ route('admin.index') }}">

            {{-- 名前 --}}
            <input type="text"
                name="keyword"
                class="admin__input"
                value="{{ request('keyword') }}"
                placeholder="名前やメールアドレスを入力して下さい。">

            {{-- 性別 --}}
            <select name="gender" class="admin__select">
                <option value="" disabled selected>性別</option>
                <option value="all" {{ request('gender') === 'all' ? 'selected' : '' }}>全て</option>
                <option value="1" {{ request('gender') == 1 ? 'selected' : '' }}>男性</option>
                <option value="2" {{ request('gender') == 2 ? 'selected' : '' }}>女性</option>
                <option value="3" {{ request('gender') == 3 ? 'selected' : '' }}>その他</option>
            </select>

            {{-- お問い合わせの種類 --}}
            <select name="category_id" class="admin__select">
                    <option value="" disabled selected>お問い合わせの種類</option>
                    <option value="all" {{ request('gender') === 'all' ? 'selected' : '' }}>全て</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->content }}
                    </option>
                @endforeach
            </select>

            {{-- 日付 --}}
            <input type="date"
                name="date"
                class="admin__date"
                value="{{ request('date') }}">

            <button type="submit" class="admin__button admin__button--search">
                検索
            </button>

            <a href="{{ route('admin.index') }}"
               class="admin__button admin__button--reset">
                リセット
            </a>
        </form>
    </div>

    <div class="admin__export-pagination">

        <!-- エクスポート -->
        <div class="admin__export">
            <a href="{{ route('admin.export', request()->query()) }}"class="admin__button admin__button--export">
                エクスポート
            </a>
        </div>
        <!-- ページネーション -->
        <div class="admin__pagination">
                    {{-- 前 --}}
                @if ($contacts->onFirstPage())
                    <span class="disabled">‹</span>
                @else
                    <a href="{{ $contacts->previousPageUrl() }}">‹</a>
                @endif

                {{-- 数字 --}}
                @for ($i = 1; $i <= $contacts->lastPage(); $i++)
                    @if ($i == $contacts->currentPage())
                        <span class="active">{{ $i }}</span>
                    @else
                        <a href="{{ $contacts->url($i) }}">{{ $i }}</a>
                    @endif
                @endfor

                {{-- 次 --}}
                @if ($contacts->hasMorePages())
                    <a href="{{ $contacts->nextPageUrl() }}">›</a>
                @else
                    <span class="disabled">›</span>
                @endif
        </div>
    </div>

    <!-- テーブル -->
    <div class="admin__table-wrapper">
        <table class="admin__table">
            <thead>
                <tr>
                    <th>お名前</th>
                    <th>性別</th>
                    <th>メールアドレス</th>
                    <th>お問い合わせの種類</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($contacts as $contact)

                <tr>
                    <td>{{ $contact->last_name }} {{ $contact->first_name }}</td>
                    <td>
                        @if($contact->gender === 1)
                            男性
                        @elseif($contact->gender === 2)
                            女性
                        @else
                            その他
                        @endif
                    </td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->category?->content }}</td>
                    <td>
                        <label for="modal-toggle-{{ $contact->id }}" class="admin__detail-button">詳細</label>

                        <!-- モーダル -->
                        <input type="checkbox" id="modal-toggle-{{ $contact->id }}" class="admin__modal-toggle">
                        <div class="admin__modal">
                            <div class="admin__modal-content">
                                <label for="modal-toggle-{{ $contact->id }}" class="admin__modal-close">×</label>

                                <div class="admin__modal-body">
                                    <div class="admin__modal-row"><span>お名前</span><span>{{ $contact->last_name }} {{ $contact->first_name }}</span></div>
                                    <div class="admin__modal-row"><span>性別</span>
                                        <span>
                                            @if($contact->gender === 1)
                                                男性
                                            @elseif($contact->gender === 2)
                                                女性
                                            @else
                                                その他
                                            @endif
                                        </span>
                                    </div>
                                    <div class="admin__modal-row"><span>メールアドレス</span><span>{{ $contact->email }}</span></div>
                                    <div class="admin__modal-row"><span>電話番号</span><span>{{ $contact->tel }}</span></div>
                                    <div class="admin__modal-row"><span>住所</span><span>{{ $contact->address }}</span></div>
                                    <div class="admin__modal-row"><span>建物名</span><span>{{ $contact->building }}</span></div>
                                    <div class="admin__modal-row"><span>お問い合わせの種類</span><span>{{ $contact->category?->content }}</span></div>
                                    <div class="admin__modal-row"><span>お問い合わせ内容</span><span>{{ $contact->detail }}</span></div>

                                    <div class="admin__modal-delete">
                                        <form method="POST" action="{{ route('admin.delete', $contact->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="admin__delete-button">削除</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>



</div>
@endsection

@section('header-buttons')
    <a href="{{ route('logout') }}" class="btn--primary">logout</a>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">

@endpush


@section('header-buttons')
    <a href="{{ route('login') }}" class="wrapper__register-button">login</a>
@endsection