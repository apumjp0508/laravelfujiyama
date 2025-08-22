<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fafafa;
        }
        .form-group input:focus {
            border-color: #666;
            box-shadow: 0 0 0 0.2rem rgba(102, 102, 102, 0.25);
            background-color: #fff;
            outline: none;
        }
        .btn {
            background-color: #333;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        .btn:hover {
            background-color: #555;
        }
        .forgot-password {
            display: block;
            margin-top: 10px;
            color: #999;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .forgot-password:hover {
            color: #666;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>管理者ログイン</h1>
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">ログイン</button>
            <a href="{{ route('password.request') }}" class="forgot-password">パスワードをお忘れですか？</a>
        </form>
    </div>
</body>
</html>