<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($badges as $badge)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset($badge->img) }}" class="card-img-top product-img" alt="{{ $badge->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $badge->name }}</h5>
                        <p class="card-text text-truncate" style="max-width: 100%;">{{ $badge->description }}</p>
                        
                      
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>