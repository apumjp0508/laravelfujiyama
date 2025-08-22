<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONFIRM ITEMS!!!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .confirm-title {
            color: #333;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

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
            z-index: -1;
        }

        .daigo_production1 {
            top: 10%;
            left: 5%;
        }

        .daigo_production2 {
            top: 20%;
            right: 10%;
        }

        .daigo_production3 {
            top: 40%;
            left: 15%;
        }

        .daigo_production4 {
            top: 60%;
            right: 20%;
        }

        .daigo_production5 {
            top: 80%;
            left: 25%;
        }

        .daigo_production6 {
            top: 30%;
            right: 5%;
        }

        @media (max-width: 768px) {
            .daigo_production {
                width: 60px;
            }
            
            .daigo_production1 { top: 5%; left: 2%; }
            .daigo_production2 { top: 15%; right: 5%; }
            .daigo_production3 { top: 35%; left: 8%; }
            .daigo_production4 { top: 55%; right: 10%; }
            .daigo_production5 { top: 75%; left: 15%; }
            .daigo_production6 { top: 25%; right: 2%; }
        }

        .product-card {
            background-color: #f7efe8;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            position: relative;
            z-index: 1;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .product-title {
            color: #111;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .product-description {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 0;
            line-height: 1.4;
        }

        .card-body {
            padding: 1rem;
        }

        .container {
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="daigo_production-container">
    <div class="daigo_production-wrap daigo_production1" id="wrap1">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production1" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production2" id="wrap2">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production2" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production3" id="wrap3">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production3" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production4" id="wrap4">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production4" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production5" id="wrap5">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production5" alt="star badge">
    </div>
    <div class="daigo_production-wrap daigo_production6" id="wrap6">
        <img src="{{ asset('img/star-badge.png') }}" class="daigo_production" id="daigo_production6" alt="star badge">
    </div>

    <div class="container mt-5 mb-5">
        <h1 class="confirm-title">商品セット確認</h1>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach($productSets as $productSet)
                <div class="col">
                    <div class="card h-100 shadow-sm product-card">
                        <img src="{{ asset($productSet->img) }}" class="card-img-top product-img" alt="{{ $productSet->name }}">
                        <div class="card-body">
                            <h5 class="card-title product-title">{{ $productSet->name }}</h5>
                            <p class="card-text product-description text-truncate" style="max-width: 100%;" title="{{ $productSet->description }}">
                                {{ $productSet->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>