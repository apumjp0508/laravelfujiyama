@extends('layouts.app') {{-- 必要に応じて layout を変更してください --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- 説明メッセージ --}}
            <div class="alert alert-info">
                パスワードをお忘れですか？<br>
                ご登録のメールアドレスを入力してください。<br>
                パスワード再設定用リンクをお送りします。
            </div>

            {{-- ステータスメッセージ --}}
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- フォーム --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- メールアドレス入力 --}}
                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input type="email" id="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 送信ボタン --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        パスワード再設定用リンクを送信
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

