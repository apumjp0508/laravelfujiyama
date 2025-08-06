<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<a href="{{route('admin.products.list')}}">トップへ戻る</a>
    <form action="{{ route('productSets.update', ['productSet' => $productSet->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <strong>名前変更</strong>
            <input type="text" name='name' value='{{$productSet->name}}'> 
        </div>
        <div>
            <strong>商品説明変更</strong>
            <textarea name="description" style="height:150px">{{ $productSet->description}}</textarea>
        </div>
        <div>
            <strong>関連商品変更</strong>
            <select name="product_id" style="width: 300px; padding: 5px;">
                <option value="">全商品共通（商品を選択しない）</option>
                @foreach($products as $product)
                    @if($product->productType === 'set')
                        <option value="{{ $product->id }}" {{ $productSet->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (ID: {{ $product->id }})
                        </option>
                    @endif
                @endforeach
            </select>
            <br><small>特定の商品専用にする場合は商品を選択してください。空白の場合は全商品で共通利用できます。</small>
        </div>
        <div>
            <strong>在庫数変更</strong>
            <input type="number" name='stock' value="{{$productSet->stock}}">
        </div>
        <div>
            <strong>画像変更</strong>
            <strong>変更前画像</strong>
            <img src="{{asset($productSet->img)}}" width="100">
            <input type="file" name='img'>
        </div>
        <div>
            <strong>横幅変更</strong>
            <input type="number" name='widthSize' value="{{$productSet->widthSize}}">
        </div>
        <div>
            <strong>縦幅変更</strong>
            <input type="number" name='heightSize' value="{{$productSet->heightSize}}">
        </div>
        <div>
            <button type='submit'>変更適用</button>
        </div>
    </form>
</body>
</html>