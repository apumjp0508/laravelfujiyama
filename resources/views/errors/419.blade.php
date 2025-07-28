<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>419 Page Expired</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center py-5">
    <h1 class="display-1 text-secondary">419</h1>
    <p class="lead">セッションの有効期限が切れました。</p>
    <p>フォームの再送信またはページ更新が必要です。</p>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3">前のページに戻る</a>
</body>
</html>
