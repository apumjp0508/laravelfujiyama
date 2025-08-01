<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<a href="{{route('products.index')}}">トップへ戻る</a>
    <form action="{{ route('badge.update',$badge->id)}}" method='POST' enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <strong>名前変更</strong>
            <input type="text" name='name' value='{{$badge->name}}'> 
        </div>
        <div>
            <strong>商品説明変更</strong>
            <textarea name="description" style="height:150px">{{ $badge->description}}</textarea>
        </div>
        <div>
            <strong>在庫数変更</strong>
            <input type="number" name='stock' value="{{$badge->stock}}">
        </div>
        <div>
            <strong>画像変更</strong>
            <strong>変更前画像</strong>
            <img src="{{asset($badge->img)}}" width="100">
            <input type="file" name='img'>
        </div>
        <div>
            <button type='submit'>変更適用</button>
        </div>
    </form>
</body>
</html>