<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品詳細</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layouts.app')

@section('content')
@auth

<div class="container mt-5 mb-5">
<h2 class="text-center mb-4">あなたのお気に入り商品一覧</h2>
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
@endauth
@endsection
</html>