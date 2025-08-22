<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }

        .mypage-title {
            color: #333;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
        }

        .menu-item {
            background-color: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .menu-icon {
            flex: 0 0 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            color:rgb(85, 82, 81);
        }

        .menu-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .menu-label {
            color: #111;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .menu-description {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .menu-arrow {
            color: #ccc;
            transition: color 0.2s ease-in-out;
        }

        .menu-item:hover .menu-arrow {
            color: #f28b82;
        }

        .menu-link {
            text-decoration: none;
            color: inherit;
        }

        .menu-link:hover {
            text-decoration: none;
            color: inherit;
        }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <h1 class="mypage-title">MY PAGE!!!</h1>

    <div class="menu-item">
        <a href="{{route('mypage.edit')}}" class="menu-link">
            <div class="d-flex align-items-center">
                <div class="menu-icon">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <div class="menu-content">
                    <label class="menu-label">会員情報の編集</label>
                    <p class="menu-description">アカウント情報の編集</p>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-chevron-right fa-lg menu-arrow"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{route('confirmOrder')}}" class="menu-link">
            <div class="d-flex align-items-center">
                <div class="menu-icon">
                    <i class="fas fa-archive fa-2x"></i>
                </div>
                <div class="menu-content">
                    <label class="menu-label">注文履歴</label>
                    <p class="menu-description">注文履歴を確認できます</p>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-chevron-right fa-lg menu-arrow"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="menu-item">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link">
            <div class="d-flex align-items-center">
                <div class="menu-icon">
                    <i class="fas fa-sign-out-alt fa-2x"></i>
                </div>
                <div class="menu-content">
                    <label class="menu-label">ログアウト</label>
                    <p class="menu-description">ログアウトします</p>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-chevron-right fa-lg menu-arrow"></i>
                </div>
            </div>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
@endsection

<script src="https://kit.fontawesome.com/a7d21f3e64.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>