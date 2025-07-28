@extends('layouts.app') {{-- 必要に応じて layout を変更 --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- エラー表示 --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- メッセージ --}}
            <div class="alert alert-info">
                登録ありがとうございます！<br>
                メールアドレスの確認リンクを送信しました。<br>
                メールが届かない場合は、以下のボタンから再送信してください。
            </div>

            {{-- 再送信成功時のメッセージ --}}
            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success">
                    登録されたメールアドレスに、新しい確認リンクを送信しました。
                </div>
            @endif

            <div class="d-flex justify-content-between mt-4">
                {{-- 確認メール再送信 --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        確認メールを再送信する
                    </button>
                </form>

                {{-- ログアウト --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger">
                        ログアウト
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
