<!DOCTYPE html>
<html lang="en">
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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <img src="{{asset( $product->img) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $product->name }}</h3>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="fw-bold text-danger fs-4">¥{{ number_format($product->price) }}</p>
                    <!-- ボタンエリア -->
                    <div class="d-grid gap-2 mt-3">
                         <form method="POST" action="{{route('carts.store')}}" class="m-3 align-items-end" id="cartForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="weight" value="1"> <!-- 重量が必要なら適切な値を設定 -->
                            
                            <label for="quantity">数量</label>
                            <input type="number" id="quantity" name="qty" min="1" value="1" class="form-control w-25">
                            <button type="submit" id='cartBtn'class="btn btn-primary btn-lg">カートに追加する</button>
                        </form>
                        @if(Auth::user()->favorite_products()->where('product_id', $product->id)->exists())
                                <a href="{{ route('favorites.destroy', $product->id) }}" class="btn samuraimart-favorite-button text-favorite w-100" onclick="event.preventDefault(); document.getElementById('favorites-destroy-form').submit();">
                                    <i class="fa fa-heart"></i>
                                    お気に入り解除
                                </a>
                            @else
                                <a href="{{ route('favorites.store', $product->id) }}" class="btn samuraimart-favorite-button text-favorite w-100" onclick="event.preventDefault(); document.getElementById('favorites-store-form').submit();">
                                    <i class="fa fa-heart"></i>
                                    お気に入り
                                </a>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="favorites-destroy-form" action="{{ route('favorites.destroy', $product->id) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')

    <button type='submit'class="btn btn-danger btn-sm">削除する</button>
</form>
<form id="favorites-store-form" action="{{ route('favorites.store', $product->id) }}" method="POST" class="d-none">
    @csrf
</form>
<a href="{{route('carts.index')}}">cart</a>
<div class="offset-1 col-11">
            <hr class="w-100">
            <h3 class="float-left">カスタマーレビュー</h3>
        </div>

        <div class="offset-1 col-10">
            <!-- レビューを実装する箇所になります -->
            <div class="row">
                @foreach($reviews as $review)
                <div class="offset-md-5 col-md-5">
                <h3 class="review-score-color">{{ str_repeat('★', $review->score) }}</h3>
                    <p class="h3">{{$review->content}}</p>
                    <label>{{$review->created_at}} {{$review->user->name}}</label>
                </div>
                @endforeach
            </div><br />

            @auth
            <div class="row">
                <div class="offset-md-5 col-md-5">
                    <form method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                        <h4>評価</h4>
                            <select name="score" class="form-control m-2 review-score-color">
                                <option value="5" class="review-score-color">★★★★★</option>
                                <option value="4" class="review-score-color">★★★★</option>
                                <option value="3" class="review-score-color">★★★</option>
                                <option value="2" class="review-score-color">★★</option>
                                <option value="1" class="review-score-color">★</option>
                            </select>
                        <h4>レビュー内容</h4>
                        @error('content')
                            <strong>レビュー内容を入力してください</strong>
                        @enderror
                        <textarea name="content" class="form-control m-2"></textarea>
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <button type="submit" class="btn samuraimart-submit-button ml-2">レビューを追加</button>
                    </form>
                </div>
            </div>
            @endauth
        </div>

<script src="{{asset('js/ajax.js')}}"></script>
<script src="{{asset('js/cartMove.js')}}"></script>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
