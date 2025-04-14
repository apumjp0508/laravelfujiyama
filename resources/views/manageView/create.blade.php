<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品追加フォーム</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>

    <div class="container mt-5">
        <!-- エラーメッセージの表示 -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="text-end mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-secondary">戻る</a>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">商品追加</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
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
                        <label for="category" class="form-label"><strong>カテゴリー</strong></label>
                        <input type="text" id="category" name="category" class="form-control" placeholder="カテゴリーを入力">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label"><strong>値段</strong></label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="値段を入力">
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label"><strong>在庫数</strong></label>
                        <input type="number" id="stock" name="stock" class="form-control" placeholder="在庫数を入力">
                    </div>
                    <div class="mb-3">
                        <label for="img" class="form-label"><strong>商品画像</strong></label>
                        <input type="file" id="img" name="img" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="productType" class="form-label"><strong>商品形式</strong></label><br>

                        <input type="radio" id="normal" name="productType" value="normal">
                        <label for="normal">普通</label><br>

                        <input type="radio" id="setProduct" name="productType" value="set">
                        <label for="setProduct">セット商品</label>
                    </div>
                    <div class="mb-3">
                        <label for="setNum" class="form-label"><strong>セット商品選択個数（通常商品の場合は選択しなくてよい）</strong></label>
                        <input type="number" id="stock" name="setNum" class="form-control" placeholder="セット商品選択個数を入力">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">追加</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
