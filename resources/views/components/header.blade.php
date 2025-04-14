<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm samuraimart-header-container">
    <div class="container">
        {{-- ロゴ --}}
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            {{ config('app.name', 'MY Original Mart') }}
        </a>

        {{-- 検索フォーム --}}
        <form action="{{ route('search') }}" method="GET" class="d-flex mx-auto w-50">
            <input name="keyword" class="form-control me-2" type="search" placeholder="商品を検索" aria-label="検索">
            <button class="btn btn-outline-primary" type="submit">検索</button>
        </form>

        {{-- メニュー項目 --}}
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto align-items-center">
                @guest
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('register') }}">登録</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="far fa-heart"></i>
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-shopping-cart"></i>
                        </a>
                    </li>
                @else
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('mypage') }}">
                            <i class="fas fa-user me-1"></i>
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('cmart.index') }}">
                            <i class="fa-solid fa-house"></i>
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{route('favorites.show')}}">
                            <i class="far fa-heart"></i>
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="{{ route('carts.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            {{-- ここにバッジを表示したい場合は↓ --}}
                            {{-- <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">3</span> --}}
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            ログアウト
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
    <!-- FontAwesome 読み込み -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</nav>
