<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coolmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>

@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">管理者メニュー</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.products.create') }}">
            <i class="fas fa-plus-circle"></i> 商品追加
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('cmart.index') }}">
            <i class="fas fa-store"></i> ショップへ移動
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('badge.index') }}">
            <i class="fas fa-th-large"></i> セット用缶バッチ管理
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('order.index') }}">
            <i class="fas fa-boxes"></i> 発注管理
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h2 class="mb-0">商品管理システム</h2>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>名前</th>
                    <th>説明</th>
                    <th>カテゴリー</th>
                    <th>値段</th>
                    <th>送料</th>
                    <th>在庫</th>
                    <th>商品形式</th>
                    <th>セット個数</th>
                    <th>画像</th>
                    <th>レビュー</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td class="text-truncate" style="max-width: 200px;">{{ $product->description }}</td>
                    <td>{{ $product->category }}</td>
                    <td>¥{{ number_format($product->price) }}</td>
                    <td>¥{{ number_format($product->shippingFee ?? 0) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <span class="badge {{ $product->productType === 'set' ? 'bg-info' : 'bg-secondary' }}">
                            {{ $product->productType }}
                        </span>
                    </td>
                    <td>
                        {{ $product->productType === 'set' ? $product->setNum : '—' }}
                    </td>
                    <td>
                        <img src="{{ asset($product->img) }}" alt="商品画像" width="80" height="80" style="object-fit:cover; border-radius: 5px;" class="img-thumbnail">
                    </td>
                    <td>
                        <a href="{{ route('products.adminReview', $product->id) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-comment-dots"></i> 編集
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endauth
<script src="https://kit.fontawesome.com/a7d21f3e64.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>