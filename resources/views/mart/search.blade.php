<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
@extends('layouts.app')

@section('content')
@auth
@if($products && $products->isNotEmpty())
    @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->price }}</td>
            <td><img src="{{ $product->img }}" width="100"></td>
        </tr>
    @endforeach
    <a href="{{ route('cmart.index') }}">top</a>
@else
    <h1>「{{ $keyword ?? '検索キーワード' }}」の検索結果は0件でした</h1>
    <a href="{{ route('cmart.index') }}">top</a>
@endif
@endauth
@endsection
</body>
</html>