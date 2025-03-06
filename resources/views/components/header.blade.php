<nav class="navbar navbar-expand-md navbar-light shadow-sm samuraimart-header-container">
   <div class="container">
       <a class="navbar-brand" href="{{ url('/') }}">
           {{ config('app.name', 'Laravel') }}
       </a>
       <div class='dropdown-center'>
            <form action="{{ route('search') }}" method="GET">
                <input name="keyword">
                <button type="submit">検索</button>
            </form>
        </div>

       <div class="collapse navbar-collapse" id="navbarSupportedContent">
           <ul class="navbar-nav ms-auto mr-5 mt-2">
               @guest
                   <li class="nav-item mr-5">
                       <a class="nav-link" href="{{ route('register') }}">登録</a>
                   </li>
                   <li class="nav-item mr-5">
                       <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                   </li>
                   <hr>
                   <li class="nav-item mr-5">
                       <a class="nav-link" href="{{ route('login') }}"><i class="far fa-heart"></i></a>
                   </li>
                   <li class="nav-item mr-5">
                       <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-shopping-cart"></i></a>
                   </li>
               @else
                   <li class="nav-item mr-5">
                        <a href="{{route('carts.index')}}">カートを見る</a>

                       <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           ログアウト
                       </a>

                       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           @csrf
                       </form>
                   </li>
                   <li class="nav-item mr-5">
                    <a class="nav-link" href="{{ route('mypage') }}">
                        <i class="fas fa-user mr-1"></i><label>マイページ</label>
                    </a>
                    </li>
                   <li class="nav-item mr-5">
                    <a class="nav-link" href="{{ route('carts.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    </li>
               @endguest
           </ul>
       </div>
   </div>
</nav>