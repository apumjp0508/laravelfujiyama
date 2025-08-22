@extends('layouts.app') {{-- 必要に応じて layout を変更してください --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- 説明メッセージ --}}
            <div class="alert forgot-password-info">
                パスワードをお忘れですか？<br>
                ご登録のメールアドレスを入力してください。<br>
                パスワード再設定用リンクをお送りします。
            </div>

            {{-- ステータスメッセージ --}}
            @if (session('status'))
                <div class="alert forgot-password-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- フォーム --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- メールアドレス入力 --}}
                <div class="mb-3">
                    <label for="email" class="form-label forgot-password-label">メールアドレス</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror forgot-password-input"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 送信ボタン --}}
                <div class="d-grid">
                    <button type="submit" class="btn forgot-password-btn">
                        パスワード再設定用リンクを送信
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<style>
/* モノトーンのパスワードリセットページスタイル */
.forgot-password-info {
    background-color: #f8f9fa;
    border-color: #ddd;
    color: #555;
    border: 1px solid #ddd;
}

.forgot-password-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.forgot-password-label {
    color: #555;
    font-weight: 500;
}

.forgot-password-input {
    border-color: #ddd;
    background-color: #fafafa;
}

.forgot-password-input:focus {
    border-color: #666;
    box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25);
    background-color: #fff;
}

.forgot-password-btn {
    background-color: #333;
    border-color: #333;
    color: #fff;
    font-weight: 500;
}

.forgot-password-btn:hover {
    background-color: #555;
    border-color: #555;
}
</style>
@endsection

