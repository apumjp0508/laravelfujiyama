<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カテゴリ検索結果</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-card {
            background-color: #f7efe8;
            display: flex;
            flex-direction: column;
            min-height: 520px;
        }

        @media screen and (max-width: 1024px) {
            .product-card {
                min-height: 480px;
            }
        }

        @media screen and (max-width: 640px) {
            .product-card {
                min-height: 440px;
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

<div class="container mt-5 mb-5">
    <h2 class="mb-4">CATEGORY SEARCH RESULT!!!</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($products as $product)
            @if($product->stock > 0)
            <div class="col">
                <div class="card h-100 shadow-sm product-card">
                    <img src="{{ asset($product->img) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title product-title fw-semibold">{{ $product->name }}</h5>
                        <p class="card-text product-desc text-truncate" title="{{ $product->description }}">
                            {{ $product->description }}
                        </p>
                        <p class="fw-bold fs-5 product-price mt-auto">¥{{ number_format($product->price + $product->shipping_fee) }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 text-center">
                        <a href="{{ route('mart.show', $product->id) }}" class="btn w-100 product-check-btn">CHECK!!!</a>
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