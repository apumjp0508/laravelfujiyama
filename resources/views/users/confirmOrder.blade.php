<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入履歴</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            height: 200px;
            object-fit: cover;
        }

        .card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .order-date {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
@auth

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">購入履歴</h2>

    @if($orderItems->isEmpty())
        <p class="text-center text-muted">購入履歴がありません。</p>
    @else
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($orderItems as $item)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset($item->product->img) }}" class="card-img-top product-img" alt="{{ $item->product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $item->product->name }}</h5>
                    <p class="order-date">購入日時：{{ $item->created_at->format('Y年m月d日 H:i') }}</p>
                    <p class="mb-1">数量：{{ $item->quantity }}</p>
                    <p class="fw-bold text-danger">合計金額：¥{{ number_format($item->product->price * $item->quantity) }}</p>
                    @if($item->shippingFee > 0)
                        <p class="text-muted small">送料：¥{{ number_format($item->shippingFee) }}</p>
                    @endif
                    <div class="mt-2">
                        @if($item->statusItem === 'shipped')
                            <span class="badge bg-success">発送済み</span>
                        @else
                            <span class="badge bg-warning">未発送</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endauth
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
