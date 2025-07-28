@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4">この中から <span class="text-primary">{{ $product->setNum }}</span> つ選んでください</h1>

    {{-- フォームはログインユーザーのみ --}}
    @auth
    <form action="{{ route('select.store') }}" method="POST">
        @csrf
    @endauth
    @guest
        <div class="text-center mt-4">
            <p class="text-danger">※ 購入するには <a href="{{ route('login') }}">ログイン</a> してください。</p>
        </div>
    @endguest
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($badges as $badge)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset($badge->img) }}" class="card-img-top product-img" alt="{{ $badge->name }}" style="object-fit: contain; height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $badge->name }}</h5>
                            <p class="card-text text-truncate">{{ $badge->description }}</p>
                            <p><strong>横幅:</strong> {{ $badge->widthSize }} mm</p>
                            <p><strong>縦幅:</strong> {{ $badge->heightSize }} mm</p>

                            @auth
                            <div class="form-check">
                                <input type="checkbox" name="select[]" value="{{ $badge->id }}" class="form-check-input checkbox" id="badge-{{ $badge->id }}">
                                <label class="form-check-label" for="badge-{{ $badge->id }}">選択する</label>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @auth
        {{-- hidden inputs and submit --}}
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="text-center mt-4">
            <button type="submit" id="submitBtn" class="btn btn-primary px-5">決定</button>
        </div>
        </form>
        @endauth
</div>
@endsection

@section('scripts')
@auth
<script src="{{ asset('js/checkbox.js') }}"></script>
@endauth
@endsection

