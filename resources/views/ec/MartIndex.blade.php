<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="css/top-page-css/mart-index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layouts.app')

@section('content')
@component('components.Top.top_main_view')
@endcomponent
<div class="brand_introduction">
  <h2 class="title">CMART OFFICIAL</h2>

  <div class="introduction_container">
    <div class="introduction_content">
      <p>CMART OFFICIALは、オフィシャルショップです。</p>
      <p>CMART OFFICIALは、オフィシャルショップです。</p>
      <p>CMART OFFICIALは、オフィシャルショップです。</p>
      <p>CMART OFFICIALは、オフィシャルショップです。</p>
      <p>CMART OFFICIALは、オフィシャルショップです。</p>
      <p>CMART OFFICIALは、オフィシャルショップです。</p>
    </div>
  </div>
</div>
<div class="brand-introduction-video" aria-hidden="true">
  <video
    src="{{ asset('videos/top_video.mp4') }}"
    width="100%" height="400"
    autoplay loop muted playsinline
    preload="auto"
    disablepictureinpicture
    controlslist="nodownload noplaybackrate nofullscreen"
    style="pointer-events:none; object-fit:cover; width:100%; height:400px;"
  >
    お使いのブラウザは動画タグに対応していません。
  </video>
</div>
<div class="Category-search">
    <h2 class="category-title">CATEGORY</h2>
    <div class="categories">
    @foreach($categories as $category)
        <div class="category-wrapper">
            <a href="{{ route('categorySearch', $category) }}" class="btn btn-outline-secondary btn-sm category-btn">
                <img src="{{ asset('img/star-badge.png') }}" alt="" class="categories-img">
                {{ $category }}
            </a>
        </div>
    @endforeach
    </div>
</div>

<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">PRODUCT</h2>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($products as $product)
        @if($product->stock > 0)
        <div class="col">
            <div class="card h-100 shadow-sm product-card">
                <img src="{{ $product->img }}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-semibold product-title">{{ $product->name }}</h5>
                    <p class="fw-bold fs-5 mt-auto product-price">¥{{ number_format($product->price) }}</p>
                </div>
                <div class="card-footer bg-white border-0 text-center">
                    @if($product->productType === 'set')
                        <a href="{{ route('select.index', $product->id) }}" class="btn w-100 product-check-btn">CHECK!!</a>
                    @else    
                        <a href="{{ route('mart.show', $product->id) }}" class="btn w-100 product-check-btn">CHECK!!</a>
                    @endif
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


