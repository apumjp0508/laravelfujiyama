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
<div class="container d-flex justify-content-center mt-3">
   <div class="w-50">
       <h1>マイページ</h1>

       <hr>

       <div class="container">
           <div class="d-flex justify-content-between">
               <div class="row">
                   <div class="col-2 d-flex align-items-center">
                       <i class="fas fa-user fa-3x"></i>
                   </div>
                   <div class="col-9 d-flex align-items-center ms-2 mt-3">
                       <div class="d-flex flex-column">
                           <label for="user-name">会員情報の編集</label>
                           <p>アカウント情報の編集</p>
                       </div>
                   </div>
               </div>
               <div class="d-flex align-items-center">
                   <a href="{{route('mypage.edit')}}">
                       <i class="fas fa-chevron-right fa-2x"></i>
                   </a>
               </div>
           </div>
       </div>

       <hr>

       <div class="container">
           <div class="d-flex justify-content-between">
               <div class="row">
                   <div class="col-2 d-flex align-items-center">
                       <i class="fas fa-archive fa-3x"></i>
                   </div>
                   <div class="col-9 d-flex align-items-center ms-2 mt-3">
                       <div class="d-flex flex-column">
                           <label for="user-name">注文履歴</label>
                           <p>注文履歴を確認できます</p>
                       </div>
                   </div>
               </div>
               <div class="d-flex align-items-center">
                   <a href="{{route('confirmOrder')}}">
                       <i class="fas fa-chevron-right fa-2x"></i>
                   </a>
               </div>
           </div>
       </div>

       <hr>

       <div class="container">
           <div class="d-flex justify-content-between">
               <div class="row">
                   <div class="col-2 d-flex align-items-center">
                       <i class="fas fa-sign-out-alt fa-3x"></i>
                   </div>
                   <div class="col-9 d-flex align-items-center ms-2 mt-3">
                       <div class="d-flex flex-column">
                           <label for="user-name">ログアウト</label>
                           <p>ログアウトします</p>
                       </div>
                   </div>
               </div>
               <div class="d-flex align-items-center">
                   <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       <i class="fas fa-chevron-right fa-2x"></i>
                   </a>

                   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                       @csrf
                   </form>
               </div>
           </div>
       </div>

       <hr>
   </div>
</div>
@endsection
<script src="https://kit.fontawesome.com/a7d21f3e64.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>