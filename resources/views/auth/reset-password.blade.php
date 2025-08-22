@extends('layouts.app') {{-- 必要に応じてレイアウトを調整 --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4 text-center reset-password-title">パスワード再設定</h2>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- パスワードリセットトークン --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- メールアドレス --}}
                <div class="mb-3">
                    <label for="email" class="form-label reset-password-label">メールアドレス</label>
                    <input id="email" type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror reset-password-input"
                        value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="mb-3">
                    <label for="password" class="form-label reset-password-label">新しいパスワード</label>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror reset-password-input"
                        required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード確認 --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label reset-password-label">パスワード再入力</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror reset-password-input"
                        required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 送信ボタン --}}
                <div class="d-grid">
                    <button type="submit" class="btn reset-password-btn">
                        パスワードを再設定する
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* モノトーンのパスワード再設定ページスタイル */
.reset-password-title {
    color: #333;
    font-weight: 600;
}

.reset-password-label {
    color: #555;
    font-weight: 500;
}

.reset-password-input {
    border-color: #ddd;
    background-color: #fafafa;
}

.reset-password-input:focus {
    border-color: #666;
    box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25);
    background-color: #fff;
}

.reset-password-btn {
    background-color: #333;
    border-color: #333;
    color: #fff;
    font-weight: 500;
}

.reset-password-btn:hover {
    background-color: #555;
    border-color: #555;
}
</style>
@endsection

