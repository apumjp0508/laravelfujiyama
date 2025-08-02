<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coolmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
@auth
<div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($selectedProductSets as $productSet)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset($productSet->img) }}" class="card-img-top product-img" alt="{{ $productSet->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $productSet->name }}</h5>
                    <p class="card-text text-truncate" style="max-width: 100%;">{{ $productSet->description }}</p>      
                </div>
            </div>
        </div>
    @endforeach
</div>
@endauth
<script src="https://kit.fontawesome.com/a7d21f3e64.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>