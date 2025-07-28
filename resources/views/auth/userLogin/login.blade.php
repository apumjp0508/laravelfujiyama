@extends('layouts.app') {{-- 必要に応じて layout を変更してください --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4 text-center">ログイン</h2>

            {{-- エラー表示 --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ステータスメッセージ --}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- メールアドレス --}}
                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="mb-3">
                    <label for="password" class="form-label">パスワード</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ログイン情報を記憶 --}}
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">ログイン情報を記憶する</label>
                </div>

                {{-- パスワードリセットリンク --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none" href="{{ route('password.request') }}">
                            パスワードを忘れた方はこちら
                        </a>
                    @endif
                </div>

                {{-- ログインボタン --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        ログイン
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

