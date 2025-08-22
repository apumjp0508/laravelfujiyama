<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お客様ログイン</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow-sm p-4 customer-login-card">
                    <h1 class="mb-4 customer-login-title">お客様ログイン</h1>
                    <p class="mb-4 customer-login-subtitle">ご利用にはログインまたは新規登録が必要です</p>

                    <div class="d-grid gap-3">
                        <a href="{{ route('login') }}" class="btn customer-login-btn">ログイン</a>
                        <a href="{{ route('register') }}" class="btn customer-register-btn">新規会員登録</a>
                        <a href="{{ route('cmart.index')}}" class="btn customer-guest-btn">ゲストログイン</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS（必要であれば） -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    /* モノトーンのお客様ログインページスタイル */
    .customer-login-card {
        background-color: #fafafa;
        border: 1px solid #ddd;
    }

    .customer-login-title {
        color: #333;
        font-weight: 600;
        font-size: 2rem;
    }

    .customer-login-subtitle {
        color: #666;
        font-size: 1.1rem;
    }

    .customer-login-btn {
        background-color: #333;
        border-color: #333;
        color: #fff;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }

    .customer-login-btn:hover {
        background-color: #555;
        border-color: #555;
        color: #fff;
    }

    .customer-register-btn {
        background-color: transparent;
        border-color: #666;
        color: #666;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }

    .customer-register-btn:hover {
        background-color: #666;
        border-color: #666;
        color: #fff;
    }

    .customer-guest-btn {
        background-color: transparent;
        border-color: #999;
        color: #999;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }

    .customer-guest-btn:hover {
        background-color: #999;
        border-color: #999;
        color: #fff;
    }
    </style>
</body>
</html>
