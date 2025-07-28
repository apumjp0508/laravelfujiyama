<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            height: 250px;
            object-fit: cover;
        }

        .card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .category-btn {
            margin: 0.2rem;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">商品一覧</h2>

    <div class="mb-4">
        <div class="fw-bold mb-2">カテゴリーから検索</div>
        @foreach($categories as $category)
            <a href="{{ route('categorySearch', $category) }}" class="btn btn-outline-secondary btn-sm category-btn">
                {{ $category }}
            </a>
        @endforeach
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($products as $product)
        @if($product->stock > 0)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ $product->img }}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary fw-semibold">{{ $product->name }}</h5>
                    <p class="card-text text-truncate" title="{{ $product->description }}">
                        {{ $product->description }}
                    </p>
                    <p class="fw-bold fs-5 text-danger mt-auto">¥{{ number_format($product->price) }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-center">
                    @if($product->productType === 'set')
                        <a href="{{ route('select.index', $product->id) }}" class="btn btn-primary w-100">詳細を見る</a>
                    @else    
                        <a href="{{ route('mart.show', $product->id) }}" class="btn btn-primary w-100">詳細を見る</a>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


