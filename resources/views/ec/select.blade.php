<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
@extends('layouts.app')

@section('content')
<h1>この中から{{$categoryNumber}}つ選んでください</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($badges as $badge)
        <form action="{{route('select.store',$badge->id)}}" method='POST'>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset($badge->img)}}" class="card-img-top product-img" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $badge->name }}</h5>
                    <p class="card-text text-truncate" style="max-width: 100%;">{{ $badge->description }}</p>
                        <input type="checkbox" name="select" class='checkbox'>
                </div>
            </div>
        </div>
        @endforeach
        <button type='submit'>決定</button>
    </form>
</div>
<script src="{{asset('js/checkbox.js')}}"></script>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>