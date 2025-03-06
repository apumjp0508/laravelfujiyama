<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layouts.app')

@section('content')
@auth

@if($products && $products->isNotEmpty())
<div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset($product->img) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-truncate" style="max-width: 100%;">{{ $product->description }}</p>
                    <p class="fw-bold text-danger">¥{{ number_format($product->price) }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-center">
                    <a href="{{route('mart.show',$product->id)}}" class="btn btn-primary w-100">詳細を見る</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <h1>「{{ $keyword ?? '検索キーワード' }}」の検索結果は0件でした</h1>
    <a href="{{ route('cmart.index') }}">top</a>
@endif
@endauth
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>