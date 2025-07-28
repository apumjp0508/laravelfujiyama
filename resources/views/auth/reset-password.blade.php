@extends('layouts.app') {{-- 必要に応じてレイアウトを調整 --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4 text-center">パスワード再設定</h2>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- パスワードリセットトークン --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- メールアドレス --}}
                <div class="mb-3">
                    <label for="email" class="form-label">メールアドレス</label>
                    <input id="email" type="email" name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード --}}
                <div class="mb-3">
                    <label for="password" class="form-label">新しいパスワード</label>
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- パスワード確認 --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">パスワード再入力</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 送信ボタン --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        パスワードを再設定する
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

