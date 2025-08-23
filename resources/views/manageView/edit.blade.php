<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <a href="{{route('admin.products.list')}}">トップへ戻る</a>
    <form action="{{ route('admin.products.update',$product->id)}}" method='POST' enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <strong>名前変更</strong>
            <input type="text" name='name' value='{{$product->name}}'> 
        </div>
        <div>
            <strong>商品説明変更</strong>
            <textarea name="description" style="height:150px">{{ $product->description}}</textarea>
        </div>
        <div>
            <strong>カテゴリー変更</strong>
            <textarea name="category" style="height:150px">{{ $product->category}}</textarea>
        </div>
        <div>
            <strong>値段変更</strong>
            <input type="number" name="price" value="{{$product->price}}">
        </div>
        <div>
            <strong>送料変更</strong>
            <input type="number" name="shippping_fee" value="{{$product->shippping_fee ?? 0}}" min="0" placeholder="送料を入力（無料の場合は0）">
        </div>
        <div>
            <strong>在庫数変更</strong>
            <input type="number" name='stock' value="{{$product->stock}}">
        </div>
        <div>
            <strong>商品タイプ</strong>
            <select name="productType">
                <option value="normal" {{ ($product->productType ?? 'normal') == 'normal' ? 'selected' : '' }}>通常商品</option>
                <option value="set" {{ ($product->productType ?? '') == 'set' ? 'selected' : '' }}>セット商品</option>
            </select>
        </div>
        <div>
            <strong>セット数</strong>
            <input type="number" name="setNum" value="{{$product->setNum ?? ''}}" min="1" placeholder="セット商品の場合のみ入力">
        </div>
        <div>
            <strong>画像変更</strong>
            <strong>変更前画像</strong>
            <img src="{{asset($product->img)}}" width="100">
            <input type="file" name='img'>
        </div>
        <div>
            <button type='submit'>変更適用</button>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>