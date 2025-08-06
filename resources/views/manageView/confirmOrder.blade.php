@extends('layouts.AdminApp')

@section('content')
<a href="{{route('order.shipped')}}">発送完了済商品一覧</a>
   <div class='card'>
    <div class='card-header bg-primary text-white'>
        <h1 class='mb-0'>未発送商品一覧</h1>
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
                <th>商品形式</th>
                <th>選択内容確認(productSetId)</th>
                <th>発送状況</th>
                <th>編集</th>
            </tr>
        </div>
        
        @foreach ($orderItems as $orderItem)
            <tr>
            
                <td>{{ $orderItem->product_name }}</td>
                <td>10</td>
                <td>{{ $orderItem->price }}</td> 
                <td>
                    <a href="{{ route('order.buyerDetails', $orderItem->user_id) }}" class="text-primary text-decoration-underline">
                        {{ $orderItem->user_id }}
                    </a>
                </td>
                <td>{{ $orderItem->productType}}</td>
                @if($orderItem->productType==='set')
                <td>
                    {{implode(', ',$orderItem->selected_product_sets)}}
                    <a href="{{route('order.confirmSet',$orderItem->id)}}">詳細確認</a>
                </td>
                @else
                <td>----</td>
                @endif
                <td>
                    @if($orderItem->statusItem === 'shipped')
                        <span class="badge bg-success">発送済み</span>
                    @else
                        <span class="badge bg-warning">未発送</span>
                    @endif
                </td>
                <td>
                    <a href="{{route('order.ship',$orderItem->id)}}">発送する</a>
                </td>
        
            </tr>
         @endforeach
        
        </table>
    </div>
</div>
@endsection