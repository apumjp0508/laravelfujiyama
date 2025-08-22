<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ショッピングカート</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .cart-title {
            color: #333;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

        .cart-summary {
            background-color: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .product-card {
            background-color: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .product-header {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-image {
            flex: 0 0 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 8px;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            color: #111;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-type {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .product-price {
            color: #777;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .product-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-btn {
            background-color: #f28b82;
            border-color: #f28b82;
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .quantity-btn:hover {
            background-color: #e07b72;
            border-color: #e07b72;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5rem;
        }

        .remove-btn {
            background-color: #e74c3c;
            border-color: #e74c3c;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }

        .remove-btn:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }

        .top-btn {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
        }

        .top-btn:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            color: #fff;
        }

        .pay-btn {
            background-color: #f28b82;
            border-color: #f28b82;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            border: none;
            font-size: 1.1rem;
        }

        .pay-btn:hover {
            background-color: #e07b72;
            border-color: #e07b72;
        }

        .tax-note {
            color: #777;
            font-size: 0.9rem;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="cart-title">SHOPPING CART!!!</h1>

    @if($cart && count($cart) > 0)
        @foreach ($cart as $product)
        <div class="product-card" id="cart-row-{{ $product->id }}">
            <div class="product-header">
                <img src="{{ asset($product->options->img ?? 'img/default-product.png') }}" 
                     alt="{{ $product->name }}" 
                     class="product-image">
                
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    
                    @if($product->options->productType == 'set' && $product->options->setNum)
                        <p class="product-type">セット商品 ({{ $product->options->setNum }}個選択)</p>
                    @endif
                    
                    @if($product->options->selectedProductSets)
                        <p class="product-type">選択済みプロダクトセット: {{ count($product->options->selectedProductSets) }}個</p>
                    @endif
                </div>
            </div>

            <div class="product-details">
                <div class="quantity-controls">
                    <button type="button" class="btn quantity-btn increment-btn number-change" data-role="increment">＋</button>
                    <input type="number" name="qty" value="{{ $product->qty }}" min="0" class="form-control quantity-input qty-input"
                        id="qty-input-{{ $product->id }}" readonly>
                    <button type="button" class="btn quantity-btn decrement-btn number-change" data-role="decrement">−</button>
                </div>

                <div class="d-flex flex-column align-items-end">
                    <p class="product-price">単価: ¥{{ number_format($product->price) }}</p>
                    <p class="product-price">小計: ¥{{ number_format($product->qty * $product->price) }}</p>
                    @if($product->options->shippingFee > 0)
                        <p class="product-type">送料: ¥{{ number_format($product->options->shippingFee) }}</p>
                    @endif
                </div>

                <div class="d-flex flex-column gap-2">
                    <form method="POST" action="{{route('carts.update')}}" class="form-product" id="cart-update-form-{{ $product->id }}">
                        @csrf
                        @method('POST')
                        <input type="hidden" id="cart-update-url" value="{{ route('carts.update') }}">
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                    </form>
                    
                    <form id="carts-destroy-form-{{ $product->id }}" action="{{route('carts.destroy',$product->rowId)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type='submit' class="btn remove-btn">削除</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        <div class="cart-summary">
            <div class="summary-row">
                <span>商品合計</span>
                <span>¥{{ number_format($total) }}</span>
            </div>
            <div class="summary-row">
                <span>合計（税込）</span>
                <span id='cart-total'>¥{{ number_format($total) }}</span>
            </div>
            <p class="tax-note">表示価格は税込みです</p>
        </div>

        <div class="action-buttons">
            <a href="{{route('cmart.index')}}" class="btn top-btn">トップに戻る</a>
            <form action="{{route('pay.index')}}" method="GET" class="d-inline">
                <input type="hidden" name="total" value="{{$total}}">
                <button type='submit' class="btn pay-btn">支払いへ進む</button>
            </form>
        </div>
    @else
        <div class="text-center mt-5">
            <h3 class="text-muted">カートに商品がありません</h3>
            <a href="{{route('cmart.index')}}" class="btn top-btn mt-3">商品を見る</a>
        </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/cartAjax.js')}}"></script>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>