<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center mt-3">
   <div class="w-75">
       <h1>ショッピングカート</h1>

       <div class="row">
           <div class="offset-8 col-4">
               <div class="row">
                   <div class="col-6">
                       <h2>数量</h2>
                   </div>
                   <div class="col-6">
                       <h2>合計</h2>
                   </div>
               </div>
           </div>
       </div>

       <hr>

       <div class="row">
           @foreach ($cart as $product)
           <div class="col-md-6 mt-4">
                <a href="{{route('mart.show', $product->id)}}">
                   <h3 class="mt-4">{{$product->name}}</h3>
               </a>
           </div>
           <div class="col-md-3">
                <a href="{{ route('products.show', $product->id) }}">
                    @if ($product->options->img)
                        <img src="{{ asset($product->options->img) }}" class="img-thumbnail">
                    @endif
                </a>
            </div>
           <form method="POST" action="{{route('carts.update')}}" class="m-3 align-items-end" id="cartForm">
                @csrf
                @method('POST')
                <input type="hidden" id="cart-update-url" value="{{ route('carts.update') }}">
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <input type="number" name='qty' value="{{$product->qty}}">
            </form>
           
           <div class="col-md-2">
            <h3 id="total-price-{{$product->id}}" class="w-100 mt-4">￥{{$product->qty * $product->price}}</h3>
           </div>
           <div class="col-md-2">
                <form id="carts-destroy-form" action="{{route('carts.destroy',$product->rowId)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type='submit'class="btn btn-danger btn-sm">削除する</button>
                </form>
            </div>    
           @endforeach
       </div>

       <hr>

       <div class="offset-8 col-4">
           <div class="row">
               <div class="col-6">
                   <h2>合計</h2>
               </div>
               <div class="col-6">
                   <h2 id='cart-total'>￥{{$total}}</h2>
               </div>
               <div class="col-12 d-flex justify-content-end">
                   表示価格は税込みです
               </div>
           </div>
       </div>

       <div>
            <a href="{{route('cmart.index')}}">トップ</a>
            <div>
                <form action="{{route('pay.index')}}">
                    <input type="hidden" value="{{$total}}">
                    <button type='submit'>支払う</button>
                </form>
            </div>
        </div>

   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('js/cartAjax.js')}}"></script>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>