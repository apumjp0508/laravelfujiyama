@extends('layouts.AdminApp')

@section('content')
<div class='card'>
    <div class='card-header bg-primary text-white'>
        <h1 class='mb-0'>発送済商品一覧</h1>
        <a href="{{route('order.index')}}"><i class="far fa-bell"></i></a>
    </div>
    <div class='dropdown-center'>
        <table class='table table-bordered'>
        <div  class='bg-dark-subtle'>
            <tr>
                <th>商品名</th>
                <th>在庫</th>         
                <th>金額</th>
                <th>購入者名</th>
                <th>発送状況</th>
                <th>操作</th>
            </tr>
        </div>
        
        @foreach ($orderItems as $orderItem)
            <tr>
            
                <td>{{ $orderItem->product_name }}</td>
                <td>10</td>
                <td>{{ $orderItem->price }}</td> 
                <td>{{ $orderItem->user_id}}</td>
                <td>発送済</td>
                <td>
                    <a href="{{ route('order.markAsUnshippedAndDelete', $orderItem->id) }}" class="btn btn-warning btn-sm" onclick="return confirm('この商品を未発送に戻しますか？')">
                        未発送に戻す
                    </a>
                </td>
            </tr>
         @endforeach
        
        </table>
    </div>
</div>
@endsection