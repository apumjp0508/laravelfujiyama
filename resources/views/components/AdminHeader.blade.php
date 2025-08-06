
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">管理者メニュー</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.products.list') }}">
            <i class="fas fa-dollar-sign"></i> home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.products.create') }}">
            <i class="fas fa-plus-circle"></i> 商品追加
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('cmart.index') }}">
            <i class="fas fa-store"></i> ショップへ移動
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('productSets.index') }}">
            <i class="fas fa-th-large"></i> セット用缶バッチ管理
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('order.index') }}">
            <i class="fas fa-boxes"></i> 発注管理
          </a>
        </li>
      </ul>
    </div>
  </div>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</nav>
