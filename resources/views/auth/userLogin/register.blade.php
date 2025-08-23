@extends('layouts.app') {{-- 必要に応じてレイアウトを調整 --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2 class="mb-4 text-center register-title">新規会員登録</h2>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                {{-- 名前 --}}
                <div class="mb-3">
                    <label for="name" class="form-label register-label">名前</label>
                    <input type="text" id="name" name="name"
                        class="form-control @error('name') is-invalid @enderror register-input"
                        value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- メールアドレス --}}
                <div class="mb-3">
                    <label for="email" class="form-label register-label">メールアドレス</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror register-input"
                        value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 郵便番号 --}}
                <div class="mb-3">
                    <label for="postal_code" class="form-label register-label">郵便番号</label>
                    <input type="text" id="postal_code" name="postal_code"
                        class="form-control @error('postal_code') is-invalid @enderror register-input"
                        value="{{ old('postal_code') }}" required>
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 住所 --}}
                <div class="mb-3">
                    <label for="address" class="form-label register-label">住所</label>
                    <input type="text" id="address" name="address"
                        class="form-control @error('address') is-invalid @enderror register-input"
                        value="{{ old('address') }}" required>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 電話番号 --}}
                <div class="mb-3">
                    <label for="phone" class="form-label register-label">電話番号</label>
                    <input type="text" id="phone" name="phone"
                        class="form-control @error('phone') is-invalid @enderror register-input"
                        value="{{ old('phone') }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="mb-3">
                    <label for="password" class="form-label register-label">パスワード</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror register-input"
                        required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード確認 --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label register-label">パスワード（確認）</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror register-input"
                        required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ログインリンクと登録ボタン --}}
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('login') }}" class="text-decoration-none register-login-link">
                        すでに登録済みの方はこちら
                    </a>
                    <button type="submit" class="btn register-btn">
                        登録する
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* モノトーンの新規会員登録ページスタイル */
.register-title {
    color: #333;
    font-weight: 600;
}

.register-label {
    color: #555;
    font-weight: 500;
}

.register-input {
    border-color: #ddd;
    background-color: #fafafa;
}

.register-input:focus {
    border-color: #666;
    box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25);
    background-color: #fff;
}

.register-login-link {
    color: #999 !important; /* 薄い灰色 */
    font-size: 0.9rem;
}

.register-login-link:hover {
    color: #666 !important;
}

.register-btn {
    background-color: #333;
    border-color: #333;
    color: #fff;
    font-weight: 500;
}

.register-btn:hover {
    background-color: #555;
    border-color: #555;
}
</style>
@endsection

