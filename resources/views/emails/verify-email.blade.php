<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール認証</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CoolMart メール認証</h1>
    </div>
    
    <div class="content">
        <p>{{ $user->name }} 様</p>
        
        <p>CoolMartにご登録いただき、ありがとうございます。</p>
        
        <p>アカウントを有効化するため、下のボタンをクリックしてメールアドレスの認証を完了してください。</p>
        
        <div style="text-align: center;">
            <a href="{{ $url }}" class="button">メールアドレスを認証する</a>
        </div>
        
        <p>上のボタンが機能しない場合は、以下のURLを直接ブラウザにコピー&ペーストしてください：</p>
        <p style="word-break: break-all; background-color: #e9ecef; padding: 10px; border-radius: 3px;">
            {{ $url }}
        </p>
        
        <p>このリンクは60分間有効です。</p>
        
        <p>もしこのメールに心当たりがない場合は、このメールを無視してください。</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} CoolMart. All rights reserved.</p>
    </div>
</body>
</html>