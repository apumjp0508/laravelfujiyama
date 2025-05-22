<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <a href="{{route('badge.create')}}">セット用缶バッチを追加する</a>
    <a href="{{route('admin.products.index')}}">戻る</a>
    <div class='dropdown-center'>
        <table class='table table-bordered'>
        <div  class='bg-dark-subtle'>
            <tr>
                <th>名前</th>
                <th>商品説明</th>
                <th>在庫</th>
                <th>画像</th>
                <th>横幅</th>
                <th>縦幅</th>
                <th>編集</th>
                <th>削除</th>
            </tr>
        </div>
        
        @foreach ($badges as $badge)
        <tr>
            <td>{{ $badge->name }}</td>
            <td>{{ $badge->description }}</td>
            <td>{{ $badge->stock}}</td>
            <td><img src="{{ asset($badge->img)}}" width="100"></td>
            <td>{{$badge->widthSize}}</td>
            <td>{{$badge->heightSize}}</td>
            <td>
                    <form action="{{ route('badge.destroy',$badge->id) }}" method="POST">
                        <a href="{{ route('badge.edit',$badge->id) }}" class='btn btn-primary btn-sm'>編集する</a>
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