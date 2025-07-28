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
                <div class="card shadow-sm p-4">
                    <h1 class="mb-4">お客様ログイン</h1>
                    <p class="mb-4">ご利用にはログインまたは新規登録が必要です</p>

                    <div class="d-grid gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">ログイン</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">新規会員登録</a>
                        <a href="{{ route('cmart.index')}}" class="btn btn-outline-primary btn-lg">ゲストログイン</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS（必要であれば） -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
