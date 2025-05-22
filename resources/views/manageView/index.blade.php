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
<a href="{{ route('admin.products.create')}}">商品追加</a>
<a href="{{route('cmart.index')}}" >ショップへ移動する</a>
<a href="{{route('badge.index')}}">セット用缶バッチ管理</a>
   <div class='card'>
    <div class='card-header bg-primary text-white'>
        <h1 class='mb-0'>商品管理システム</h1>
        <a href="{{route('order.index')}}">発注</a>
    </div>
    <div class='dropdown-center'>
        <table class='table table-bordered'>
        <div  class='bg-dark-subtle'>
            <tr>
                <th>名前</th>
                <th>商品説明</th>
                <th>カテゴリー</th>
                <th>値段</th>
                <th>在庫</th> 
                <th>商品形式</th>
                <th>セット商品選択個数</th>
                <th>画像</th>
                <th>レビュー一覧</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        </div>
        
        @foreach ($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->category}}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock}}</td>
            <td>{{ $product->productType}}</td>
            @if($product->productType==='normal')
            <td>------</td>
            @else
            <td>{{ $product->setNum}}</td>
            @endif
            <td><img src="{{ asset($product->img)}}" width="100"></td>
            <td><a href="{{route('products.adminReview',$product->id)}}">レビューを編集する</a></td>
            <td>
                    <form action="{{ route('admin.products.destroy',$product->id) }}" method="POST">
                        <a href="{{ route('admin.products.edit',$product->id) }}" class='btn btn-primary btn-sm'>編集する</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">削除する</button>
                    </form>
                </td>
        </tr>
        @endforeach
        </table>
    </div>
</div>
@endauth
<script src="https://kit.fontawesome.com/a7d21f3e64.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>