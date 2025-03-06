<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 画像のサイズ調整 */
        .product-img {
            height: 250px;
            object-fit: cover; /* 画像の比率を維持しながらトリミング */
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
@auth

<div class="container mt-5">
    <h2 class="text-center mb-4">商品一覧</h2>
    <div>
        <div>カテゴリーから検索</div>
        @foreach($categories as $category)
            <a href="{{route('categorySearch',$category)}}">{{$category}}</a>
        @endforeach
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ $product->img }}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-truncate" style="max-width: 100%;">{{ $product->description }}</p>
                    <p class="fw-bold text-danger">¥{{ number_format($product->price) }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-center">
                    @foreach($keywords as $keyword)
                    <form method='POST'>
                        @if($product==$keyword)
                            <a href="{{route('select.index',$product->id)}}" class="btn btn-primary w-100">詳細を見る</a>
                        @else    
                            <a href="{{route('mart.show',$product->id)}}" class="btn btn-primary w-100">詳細を見る</a>
                        @endif
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    
</div>

@endauth
@endsection

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</body>
</html>

