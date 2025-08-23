<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>お気に入り商品</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .favorite-title {
            color: #333;
            font-weight: 600;
        }

        .product-card {
            background-color: #f7efe8;
            display: flex;
            flex-direction: column;
            min-height: 620px;
        }

        @media screen and (max-width: 1024px) {
            .product-card {
                min-height: 580px;
            }
        }

        @media screen and (max-width: 640px) {
            .product-card {
                min-height: 540px;
            }
        }

        .product-card .product-img {
            flex: 0 0 60%;
            height: 60%;
            object-fit: contain;
            width: 100%;
        }

        .product-title {
            color: #111;
        }

        .product-desc {
            color: #777;
            margin-bottom: 0;
        }

        .product-price {
            color: #777;
            margin-bottom: 0;
        }

        .product-card .card-body {
            padding-bottom: 0.5rem;
        }

        .product-card .card-footer {
            padding-top: 0.25rem;
            padding-bottom: 0.5rem;
            margin-top: auto;
        }

        .product-check-btn {
            background-color: #f28b82;
            border-color: #f28b82;
            color: #fff;
            padding-top: 0.4rem;
            padding-bottom: 0.4rem;
            display: block;
            width: 100%;
        }

        .product-check-btn:hover {
            background-color: #e07b72;
            border-color: #e07b72;
        }

        .remove-favorite-btn {
            background-color: transparent;
            border-color: #e74c3c;
            color: #e74c3c;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .remove-favorite-btn:hover {
            background-color: #e74c3c;
            border-color: #e74c3c;
            color: #fff;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
@auth

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4 favorite-title">YOUR FAVORITE ITEMS!!!</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($products as $product)
        @if($product->stock > 0)
        <div class="col">
            <div class="card h-100 shadow-sm product-card">
                <img src="{{ asset($product->img) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title product-title fw-semibold">{{ $product->name }}</h5>              
                    <p class="fw-bold fs-5 product-price mt-auto">¥{{ number_format($product->price + $product->shipping_fee) }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-center">
                    @if($product->productType === 'set')
                        <a href="{{ route('select.index', $product->id) }}" class="btn w-100 product-check-btn">CHECK!!</a>
                    @else    
                        <a href="{{ route('mart.show', $product->id) }}" class="btn w-100 product-check-btn">CHECK!!</a>
                    @endif
                    
                    {{-- お気に入り解除ボタン --}}
                    <form method="POST" action="{{ route('favorites.destroy', ['products_id' => $product->id]) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn w-100 remove-favorite-btn" 
                                onclick="return confirm('お気に入りから削除しますか？')">
                            <i class="fa fa-heart"></i> お気に入り解除
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endauth
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>