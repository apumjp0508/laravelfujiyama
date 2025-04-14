<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coolmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
@auth
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
                <th>選択内容確認(badgeId)</th>
                <th>発送状況</th>
                <th>編集</th>
            </tr>
        </div>
        
        @foreach ($orderItems as $orderItem)
            <tr>
            
                <td>{{ $orderItem->product_name }}</td>
                <td>10</td>
                <td>{{ $orderItem->price }}</td> 
                <td>{{ $orderItem->user_id}}</td>
                <td>{{ $orderItem->productType}}</td>
                @if($orderItem->productType==='set')
                <td>
                    {{implode(', ',$orderItem->selected_badges)}}
                    <a href="{{route('order.confirmSet',$orderItem->id)}}">詳細確認</a>
                </td>
                @else
                <td>----</td>
                @endif
                <td>未発送</td>
                <td>
                    <a href="{{route('order.ship',$orderItem->id)}}">発送する</a>
                </td>
        
            </tr>
         @endforeach
        
        </table>
    </div>
</div>
@endauth
<script src="https://kit.fontawesome.com/a7d21f3e64.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>