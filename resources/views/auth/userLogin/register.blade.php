@extends('layouts.app') {{-- 必要に応じてレイアウトを調整 --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2 class="mb-4 text-center">新規会員登録</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- 名前 --}}
                <div class="mb-3">
                    <label for="name" class="form-label">名前</label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- メールアドレス --}}
                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 郵便番号 --}}
                <div class="mb-3">
                    <label for="postal_code" class="form-label">郵便番号</label>
                    <input type="text" id="postal_code" name="postal_code"
                        class="form-control @error('postal_code') is-invalid @enderror"
                        value="{{ old('postal_code') }}" required>
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 住所 --}}
                <div class="mb-3">
                    <label for="address" class="form-label">住所</label>
                    <input type="text" id="address" name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        value="{{ old('address') }}" required>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 電話番号 --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">電話番号</label>
                    <input type="text" id="phone" name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="mb-3">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード確認 --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">パスワード（確認）</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ログインリンクと登録ボタン --}}
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        すでに登録済みの方はこちら
                    </a>
                    <button type="submit" class="btn btn-primary">
                        登録する
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

