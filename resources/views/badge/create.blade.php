<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
<form action="{{ route('badge.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="mb-3">
            <label for="name" class="form-label"><strong>商品名</strong></label>
            <input type="text" id="name" name="name" class="form-control" placeholder="商品名を入力">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label"><strong>商品説明</strong></label>
            <input type="text" id="description" name="description" class="form-control" placeholder="商品説明を入力">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label"><strong>在庫数</strong></label>
            <input type="number" id="stock" name="stock" class="form-control" placeholder="在庫数を入力">
        </div>
        <div class="mb-3">
            <label for="img" class="form-label"><strong>商品画像</strong></label>
            <input type="file" id="img" name="img" class="form-control">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">追加</button>
        </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>