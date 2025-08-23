<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品詳細</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(116, 46, 46);
        }

        .product-img {
            height: 350px;
            object-fit: contain;
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

        /* モノトーンの商品詳細ページスタイル */
        .product-title {
            color: #333;
            font-weight: 600;
        }

        .product-description {
            color: #555;
        }

        .product-price {
            color: #333;
            font-weight: 600;
        }

        .shipping-fee {
            color: #666;
        }

        .set-content-link {
            color: #999;
            text-decoration: none;
        }

        .set-content-link:hover {
            color: #666;
            text-decoration: underline;
        }

        .quantity-label {
            color: #555;
            font-weight: 500;
        }

        .quantity-input {
            border-color: #ddd;
            background-color: #fafafa;
        }

        .quantity-input:focus {
            border-color: #666;
            box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25);
            background-color: #fff;
        }

        .cart-btn {
            background-color: #333;
            border-color: #333;
            color: #fff;
            font-weight: 500;
        }

        .cart-btn:hover {
            background-color: #555;
            border-color: #555;
        }

        .login-required-text {
            color: #dc3545;
        }

        .login-cart-btn {
            background-color: transparent;
            border-color: #666;
            color: #666;
            font-weight: 500;
        }

        .login-cart-btn:hover {
            background-color: #666;
            border-color: #666;
            color: #fff;
        }

        .favorite-btn {
            background-color: transparent;
            border-color: #e74c3c;
            color: #e74c3c;
            font-weight: 500;
        }

        .favorite-btn:hover {
            background-color: #e74c3c;
            border-color: #e74c3c;
            color: #fff;
        }

        .guest-favorite-btn {
            background-color: transparent;
            border-color: #999;
            color: #999;
            font-weight: 500;
        }

        .guest-favorite-btn:hover {
            background-color: #999;
            border-color: #999;
            color: #fff;
        }

        .cart-view-btn {
            background-color: transparent;
            border-color: #666;
            color: #666;
            font-weight: 500;
        }

        .cart-view-btn:hover {
            background-color: #666;
            border-color: #666;
            color: #fff;
        }

        .review-title {
            color: #333;
            font-weight: 600;
        }

        .review-form-label {
            color: #555;
            font-weight: 500;
        }

        .review-textarea {
            border-color: #ddd;
            background-color: #fafafa;
        }

        .review-textarea:focus {
            border-color: #666;
            box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25);
            background-color: #fff;
        }

        .review-submit-btn {
            background-color: #333;
            border-color: #333;
            color: #fff;
            font-weight: 500;
        }

        .review-submit-btn:hover {
            background-color: #555;
            border-color: #555;
        }

        /* daigo_production の配置スタイル */
        .daigo_production-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
        }

        .daigo_production {
            width: 80px;
            height: auto;
            display: block;
            border-radius: 50%;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
        }

        .daigo_production-wrap {
            position: absolute;
            z-index: -1; /* 後ろに配置 */
        }

        .daigo_production1 {
            top: 10%;
            left: 5%;
        }

        .daigo_production2 {
            top: 20%;
            right: 8%;
        }

        .daigo_production3 {
            top: 60%;
            left: 3%;
        }

        .daigo_production4 {
            top: 70%;
            right: 5%;
        }

        .daigo_production5 {
            top: 40%;
            left: 50%;
            transform: translateX(-50%);
        }

        .daigo_production6 {
            top: 85%;
            left: 50%;
            transform: translateX(-50%);
        }

        /* レスポンシブ対応 */
        @media screen and (max-width: 768px) {
            .daigo_production {
                width: 60px;
            }
            
            .daigo_production1 { top: 5%; left: 2%; }
            .daigo_production2 { top: 15%; right: 3%; }
            .daigo_production3 { top: 55%; left: 1%; }
            .daigo_production4 { top: 65%; right: 2%; }
            .daigo_production5 { top: 35%; left: 50%; }
            .daigo_production6 { top: 80%; left: 50%; }
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="daigo_production-container">
    <!-- daigo_production の配置 -->
    <div class="daigo_production-wrap daigo_production1" id="wrap1">
        <img src="{{ asset('img/pink-and-blue-badge.png') }}" class="daigo_production" id="daigo_production1" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production2" id="wrap2">
        <img src="{{ asset('img/like-earth-badge.png') }}" class="daigo_production" id="daigo_production2" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production3" id="wrap3">
        <img src="{{ asset('img/yossy-badge.png') }}" class="daigo_production" id="daigo_production3" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production4" id="wrap4">
        <img src="{{ asset('img/logo-badge.png') }}" class="daigo_production" id="daigo_production4" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production5" id="wrap5">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production5" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production6" id="wrap6">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production6" alt="star badge">
    </div>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <img src="{{ asset($product->img) }}" class="card-img-top product-img" alt="{{ $product->name }}">
                    <div class="card-body text-center">
                        <h3 class="card-title product-title">{{ $product->name }}</h3>
                        <p class="card-text product-description">{{ $product->description }}</p>
                        <p class="fw-bold product-price fs-4">¥{{ number_format($product->price+$product->shipping_fee) }}</p>
                            <p class="shipping-fee">内送料：¥{{ number_format($product->shipping_fee) }}</p>

                        <div class="d-grid gap-2 mt-4">
                            @auth
                            <form method="POST" action="{{ route('carts.add') }}" id="cartForm">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="img" value="{{ $product->img }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                <input type="hidden" name="shipping_fee" value="{{ $product->shipping_fee ?? 0 }}">
                                <input type="hidden" name="stock" value="{{ $product->stock }}">
                                <input type="hidden" name="setNum" value="{{ $product->setNum }}">
                                <input type="hidden" name="productType" value="{{ $product->productType }}">
                                @if($selectedProductSets)
                                    @foreach($selectedProductSets as $productSet)
                                        <input type="hidden" name="selectedProductSets[]" value="{{ $productSet }}">
                                    @endforeach
                                @endif
                                <input type="hidden" name="weight" value="1">
                                
                                @if($product->productType === 'set')
                                    <a href="{{ route('confirmItems', [$product->id, 'selectedProductSets' => $selectedProductSets, 'setId' => $setId]) }}" class="set-content-link">
                                        セット内容を見る
                                    </a>
                                @endif

                                <div class="mb-3">
                                    <label for="quantity" class="form-label quantity-label">数量</label>
                                    <input type="number" id="quantity" name="qty" min="1" value="1" class="form-control w-25 mx-auto quantity-input">
                                </div>

                                <button type="submit" class="btn btn-lg w-100 cart-btn" id="cartBtn">カートに追加する</button>
                            </form>
                            @endauth

                            @guest
                                <div class="mt-4">
                                    <p class="text-center login-required-text">カートに追加するにはログインが必要です。</p>
                                    <a href="{{ route('login') }}" class="btn btn-lg w-100 login-cart-btn">ログインしてカートに追加</a>
                                </div>
                            @endguest

                          {{-- お気に入り機能：ログインユーザーのみ表示 --}}
                            @auth
                                @if(Auth::user()->favorite_products()->where('product_id', $product->id)->exists())
                                    <a href="{{ route('favorites.destroy', ['products_id' => $product->id]) }}" class="btn w-100 mt-2 favorite-btn"
                                        onclick="event.preventDefault(); document.getElementById('favorites-destroy-form').submit();">
                                        <i class="fa fa-heart"></i> お気に入り解除
                                    </a>
                                @else
                                    <a href="{{ route('favorites.store', ['products_id' => $product->id]) }}" class="btn w-100 mt-2 favorite-btn"
                                        onclick="event.preventDefault(); document.getElementById('favorites-store-form').submit();">
                                        <i class="fa fa-heart"></i> お気に入りに追加
                                    </a>
                                @endif
                            @endauth

                            @guest
                                {{-- ゲストにはハートボタンを表示せず、ログイン誘導する等も可能 --}}
                                <a href="{{ route('login') }}" class="btn w-100 mt-2 guest-favorite-btn">
                                    <i class="fa fa-heart"></i> お気に入り登録にはログインが必要です
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <a href="{{ route('carts.index') }}" class="btn cart-view-btn">カートを見る</a>
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
                <h3 class="text-center review-title">REVIEW!!!!</h3>
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
                    <select name="score" class="form-select mb-3 review-score-color">
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★</option>
                        <option value="3">★★★</option>
                        <option value="2">★★</option>
                        <option value="1">★</option>
                    </select>

                    <h4 class="review-form-label">COMMENT</h4>
                    @error('content')
                        <p class="text-danger">※ レビュー内容を入力してください</p>
                    @enderror
                    <textarea name="content" class="form-control mb-3 review-textarea" rows="3"></textarea>
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn w-100 review-submit-btn">投稿</button>
                </form>
            </div>
            @endauth
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/ajax.' . (config('app.debug') ? 'development' : 'production') . '.js') }}"></script>
<script src="{{ asset('js/cartMove.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

