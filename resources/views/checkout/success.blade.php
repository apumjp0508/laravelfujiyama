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
<div class="container pt-5">
   <div class="row justify-content-center">
       <div class="col-md-5">
           <h1 class="text-center mb-3">ご注文ありがとうございます！</h3>

           <p class="text-center lh-lg mb-5">
               商品が到着するまでしばらくお待ち下さい。
           </p>

           <div class="text-center">
               <a href="{{ url('/') }}" class="btn samuraimart-submit-button w-75 text-white">トップページへ</a>
           </div>
       </div>
   </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</body>
</html>