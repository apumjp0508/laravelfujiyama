<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h2>{{$product->name}}のレビュー一覧</h2>
    @foreach($reviews as $review)
        <div class="offset-md-5 col-md-5">
            <h3 class="review-score-color">{{ str_repeat('★', $review->score) }}</h3>
                <p class="h3">{{$review->content}}</p>
            <label>{{$review->created_at}} {{$review->user->name}}</label>
            <form action="{{route('products.deleteReview',$review->id)}}" method='POST'>
                @csrf
                @method('DELETE')
                <button type="submit" >削除する</button>
            </form>
        </div>
    @endforeach
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>