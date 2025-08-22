@extends('layouts.app') {{-- 必要に応じて layout を変更 --}}

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- エラー表示 --}}
            @if (session('error'))
                <div class="alert verify-email-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- メッセージ --}}
            <div class="alert verify-email-info">
                登録ありがとうございます！<br>
                メールアドレスの確認リンクを送信しました。<br>
                メールが届かない場合は、以下のボタンから再送信してください。
            </div>

            {{-- 再送信成功時のメッセージ --}}
            @if (session('status') == 'verification-link-sent')
                <div class="alert verify-email-success">
                    登録されたメールアドレスに、新しい確認リンクを送信しました。
                </div>
            @endif

            <div class="d-flex justify-content-between mt-4">
                {{-- 確認メール再送信 --}}
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn verify-email-btn">
                        確認メールを再送信する
                    </button>
                </form>

                {{-- ログアウト --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn verify-email-logout-btn">
                        ログアウト
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
/* モノトーンのメール確認ページスタイル */
.verify-email-error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.verify-email-info {
    background-color: #f8f9fa;
    border-color: #ddd;
    color: #555;
    border: 1px solid #ddd;
}

.verify-email-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.verify-email-btn {
    background-color: #333;
    border-color: #333;
    color: #fff;
    font-weight: 500;
}

.verify-email-btn:hover {
    background-color: #555;
    border-color: #555;
}

.verify-email-logout-btn {
    background-color: transparent;
    border-color: #dc3545;
    color: #dc3545;
    font-weight: 500;
}

.verify-email-logout-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: #fff;
}
</style>
@endsection
