<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <a href="{{route('productSets.create')}}">セット用缶バッチを追加する</a>
    <a href="{{route('admin.products.list')}}">戻る</a>
    <div class='dropdown-center'>
        <table class='table table-bordered'>
        <div  class='bg-dark-subtle'>
            <tr>
                <th>名前</th>
                <th>商品説明</th>
                <th>在庫</th>
                <th>関連商品</th>
                <th>画像</th>
                <th>横幅</th>
                <th>縦幅</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        </div>
        
        @foreach ($productSets as $productSet)
        <tr>
            <td>{{ $productSet->name }}</td>
            <td>{{ $productSet->description }}</td>
            <td>{{ $productSet->stock}}</td>
            <td>
                @if($productSet->product_id)
                    <span class="badge bg-info">{{ $productSet->product->name ?? 'ID: ' . $productSet->product_id }}</span>
                @else
                    <span class="badge bg-secondary">全商品共通</span>
                @endif
            </td>
            <td><img src="{{ asset($productSet->img)}}" width="100"></td>
            <td>{{$productSet->widthSize}}</td>
            <td>{{$productSet->heightSize}}</td>
            <td>
                    <form action="{{ route('productSets.destroy',$productSet->id) }}" method="POST">
                        <a href="{{ route('productSets.edit',$productSet->id) }}" class='btn btn-primary btn-sm'>編集する</a>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">削除する</button>
                    </form>
                </td>
        </tr>
        @endforeach
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>