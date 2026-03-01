@extends('layouts.app')
@section('show-title', true)

@section('title', 'お問い合わせフォーム')

@push('css')
<link rel="stylesheet" href="{{ asset('css/contact/contact.css') }}">
@endpush

@section('content')

<div class="contact">
    <h2 class="contact__subtitle">Contact</h2>

    <form method="POST" action="{{ route('contact.confirm') }}">
        @csrf

        {{-- お名前 --}}
        <div class="contact-form__group">
            <label>お名前 <span class="required">※</span></label>

            <div>
                <div class="contact-form__name">
                    <input type="text" name="last_name"
                        placeholder="例: 山田"
                        value="{{ old('last_name', $contact['last_name'] ?? '') }}">

                    <input type="text" name="first_name"
                        placeholder="例: 太郎"
                        value="{{ old('first_name', $contact['first_name'] ?? '') }}">
                </div>
                @error('last_name') <div class="contact-form__error">{{ $message }}</div> @enderror
                @error('first_name') <div class="contact-form__error">{{ $message }}</div> @enderror  
            </div>      
        </div>

        {{-- 性別 --}}
        <div class="contact-form__group">
            <label>性別 <span class="required">※</span></label>

            <div>
                <div class="contact-form__radio">
                    <label>
                        <input type="radio" name="gender" value="1"
                            {{ old('gender', $contact['gender'] ?? '') == '1' ? 'checked' : '' }}>
                        男性
                    </label>

                    <label>
                        <input type="radio" name="gender" value="2"
                            {{ old('gender', $contact['gender'] ?? '') == '2' ? 'checked' : '' }}>
                        女性
                    </label>

                    <label>
                        <input type="radio" name="gender" value="3"
                            {{ old('gender', $contact['gender'] ?? '') == '3' ? 'checked' : '' }}>
                        その他
                    </label>
                </div>
                @error('gender') <div class="contact-form__error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- メール --}}
        <div class="contact-form__group">
            <label>メールアドレス <span class="required">※</span></label>

            <div>
                <input type="email" name="email"
                    placeholder="例: test@example.com"
                    value="{{ old('email', $contact['email'] ?? '') }}">
                @error('email') <div class="contact-form__error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- 電話番号 --}}
        <div class="contact-form__group">
            <label>電話番号 <span class="required">※</span></label>

            <div>
                <div class="contact-form__tel">
                    <input type="text" name="tel1"
                        placeholder="080"
                        value="{{ old('tel1', $contact['tel1'] ?? '') }}">
                    <span>-</span>
                    <input type="text" name="tel2"
                        placeholder="1234"
                        value="{{ old('tel2', $contact['tel2'] ?? '') }}">
                    <span>-</span>
                    <input type="text" name="tel3"
                        placeholder="5678"
                        value="{{ old('tel3', $contact['tel3'] ?? '') }}">
                </div>
                @error('tel1') <div class="contact-form__error">{{ $message }}</div> @enderror
                @error('tel2') <div class="contact-form__error">{{ $message }}</div> @enderror
                @error('tel3') <div class="contact-form__error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- 住所 --}}
        <div class="contact-form__group">
            <label>住所 <span class="required">※</span></label>

            <div>
                <input type="text" name="address"
                    placeholder="例: 東京都渋谷区千駄ヶ谷1-2-3"
                    value="{{ old('address', $contact['address'] ?? '') }}">
                @error('address') <div class="contact-form__error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- 建物名 --}}
        <div class="contact-form__group">
            <label>建物名</label>

            <div>
                <input type="text" name="building"
                    placeholder="例: 千駄ヶ谷マンション101"
                    value="{{ old('building', $contact['building'] ?? '') }}"> 
            </div>
        </div>

        {{-- お問い合わせ種類 --}}
        <div class="contact-form__group">
            <label>お問い合わせの種類 <span class="required">※</span></label>

            <div>
                <select name="contact_type">
                    <option value="" disabled selected>お問い合わせの種類</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('contact_type', $contact['contact_type'] ?? '') == $category->id ? 'selected' : '' }}>
                            {{ $category->content }}
                        </option>
                    @endforeach
                </select>
                @error('contact_type') <div class="contact-form__error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- お問い合わせ内容 --}}
        <div class="contact-form__group contact-form__group--textarea">
            <label>お問い合わせ内容 <span class="required">※</span></label>

            <div>
            <textarea name="detail" rows="5" placeholder="お問い合わせ内容をご記載ください">{{ old('detail', $contact['detail'] ?? '') }}</textarea>
            @error('detail') <div class="contact-form__error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="contact-form__button">
            <button type="submit">確認画面</button>
        </div>

    </form>

</div>
@endsection