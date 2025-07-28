<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品詳細</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            height: 350px;
            object-fit: cover;
        }

        .card:hover {
            transform: scale(1.01);
            transition: 0.3s ease-in-out;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        }

        .review-score-color {
            color: #f39c12;
        }

        .text-favorite {
            color: #e74c3c;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <img src="{{ asset($product->img) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body text-center">
                    <h3 class="card-title fw-bold">{{ $product->name }}</h3>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="fw-bold text-danger fs-4">¥{{ number_format($product->price) }}</p>

                    <div class="d-grid gap-2 mt-4">
                        @auth
                        <form method="POST" action="{{ route('carts.store') }}" id="cartForm">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="img" value="{{ $product->img }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="stock" value="{{ $product->stock }}">
                            <input type="hidden" name="setNum" value="{{ $product->setNum }}">
                            <input type="hidden" name="productType" value="{{ $product->productType }}">
                            @if($selectedBadges)
                                @foreach($selectedBadges as $badge)
                                    <input type="hidden" name="selectedBadges[]" value="{{ $badge }}">
                                @endforeach
                            @endif
                            <input type="hidden" name="weight" value="1">

                            @if($product->productType === 'set')
                                <a href="{{ route('confirmItems', ['product' => $product->id, 'selectedBadges' => $selectedBadges]) }}" class="btn btn-secondary mb-3">
                                    セット内容を見る
                                </a>
                            @endif

                            <div class="mb-3">
                                <label for="quantity" class="form-label">数量</label>
                                <input type="number" id="quantity" name="qty" min="1" value="1" class="form-control w-25 mx-auto">
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100" id="cartBtn">カートに追加する</button>
                        </form>
                        @endauth

                        @guest
                            <div class="mt-4">
                                <p class="text-center text-danger">カートに追加するにはログインが必要です。</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100">ログインしてカートに追加</a>
                            </div>
                        @endguest

                      {{-- お気に入り機能：ログインユーザーのみ表示 --}}
                        @auth
                            @if(Auth::user()->favorite_products()->where('product_id', $product->id)->exists())
                                <a href="{{ route('favorites.destroy', ['products_id' => $product->id]) }}" class="btn btn-outline-danger w-100 mt-2"
                                    onclick="event.preventDefault(); document.getElementById('favorites-destroy-form').submit();">
                                    <i class="fa fa-heart"></i> お気に入り解除
                                </a>
                            @else
                                <a href="{{ route('favorites.store', ['products_id' => $product->id]) }}" class="btn btn-outline-danger w-100 mt-2"
                                    onclick="event.preventDefault(); document.getElementById('favorites-store-form').submit();">
                                    <i class="fa fa-heart"></i> お気に入りに追加
                                </a>
                            @endif
                        @endauth

                        @guest
                            {{-- ゲストにはハートボタンを表示せず、ログイン誘導する等も可能 --}}
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fa fa-heart"></i> お気に入り登録にはログインが必要です
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('carts.index') }}" class="btn btn-outline-secondary">カートを見る</a>
            </div>
        </div>
    </div>

    {{-- お気に入り用フォーム --}}
    <form id="favorites-destroy-form" action="{{ route('favorites.destroy', $product->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    <form id="favorites-store-form" action="{{ route('favorites.store', $product->id) }}" method="POST" class="d-none">
        @csrf
    </form>

    {{-- レビュー --}}
    <div class="row mt-5">
        <div class="col-12">
            <hr>
            <h3 class="text-center">カスタマーレビュー</h3>
        </div>

        @foreach($reviews as $review)
        <div class="col-md-6 offset-md-3 mb-4">
            <div class="border rounded p-3 bg-light">
                <h5 class="review-score-color">{{ str_repeat('★', $review->score) }}</h5>
                <p class="mb-1">{{ $review->content }}</p>
                <small class="text-muted">{{ $review->created_at->format('Y/m/d') }} | {{ $review->user->name }}</small>
            </div>
        </div>
        @endforeach

        @auth
        <div class="col-md-6 offset-md-3 mt-4">
            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <h4>評価</h4>
                <select name="score" class="form-select mb-3 review-score-color">
                    <option value="5">★★★★★</option>
                    <option value="4">★★★★</option>
                    <option value="3">★★★</option>
                    <option value="2">★★</option>
                    <option value="1">★</option>
                </select>

                <h4>レビュー内容</h4>
                @error('content')
                    <p class="text-danger">※ レビュー内容を入力してください</p>
                @enderror
                <textarea name="content" class="form-control mb-3" rows="3"></textarea>
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-success w-100">レビューを投稿</button>
            </form>
        </div>
        @endauth
    </div>
</div>
@endsection

<script src="{{ asset('js/ajax.js') }}"></script>
<script src="{{ asset('js/cartMove.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

