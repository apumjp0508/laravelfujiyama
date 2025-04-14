<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果</title>
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
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
@auth

<div class="container mt-5 mb-5">

@if($products && $products->isNotEmpty())
    <h2 class="mb-4">検索結果</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($products as $product)
            @if($product->stock > 0)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset($product->img) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary fw-semibold">{{ $product->name }}</h5>
                        <p class="card-text text-truncate" title="{{ $product->description }}">
                            {{ $product->description }}
                        </p>
                        <p class="fw-bold fs-5 text-danger mt-auto">¥{{ number_format($product->price) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 text-center">
                        <a href="{{ route('mart.show', $product->id) }}" class="btn btn-primary w-100">詳細を見る</a>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
@else
    <div class="text-center mt-5">
        <h3 class="text-muted">「{{ $keyword ?? '検索キーワード' }}」の検索結果は0件でした。</h3>
        <a href="{{ route('cmart.index') }}" class="btn btn-outline-secondary mt-3">トップに戻る</a>
    </div>
@endif

</div>
@endauth
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>

