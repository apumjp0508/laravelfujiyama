# MY Original Market
初めて、アパレルECサイトを個人開発をしたので、紹介します。<br/>
![5JlVjL7s1WFMffbqDgBI1744782396-1744782437](https://github.com/user-attachments/assets/89e177d5-16e9-49da-98b1-c8115e46d291)
# 概要
ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>

# 制作背景
自分の服にすごい熱量を注いでいる友人がアクセサリ－を自作しており、それを販売したいとのことだったので、laravelを学習し始めたばかりの自分にとってすごくいい成長の機会だったため、ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>
また、このECサイトはプログラミングスクール侍のlaravelでAmazon風アプリを作ろうを参考にしています。<br/>
友人の方から、缶バッチをセット形式で販売したいため、数ある缶バッチの中から、4つ選んで購入できるようにしてほしいとの要望があり、それを実装しました。
# ECサイト　画面遷移図
![スクリーンショット 2025-06-05 205829](https://github.com/user-attachments/assets/9c399c16-03a1-4afc-9d9a-de771f653d27)<br/>
管理者用ページや顧客用ページを分けることでmiddlewareの概念やrouteとの違いについて学ぶことができました！
# 開発環境について
## XAMPPを用いて開発環境構築を行いました
- Aache/2.4.58(Win64)
- PHP/8.2.12
- MySQL
これで開発を行っていたのですが、ちょうど作り終わった段階で、Dockerについて学び始め、Dockerコンテナを導入し始めました。<br/>
----Docker Image----<br/>
  laravel_appコンテナ<br/>
  　　　Laravel<br/>
  laravel_nginxコンテナ<br/>
  　　　nginx<br/>
---- Dockerfile----<br/>
  php-fpm,compose,node.js,その他PHP拡張とツールをインストール
  ## docker環境にするのに苦労した点
  DockerDesktopをインストールして,docker-compose.ymlとDockerfileを書いて、いざリポジトリにプッシュしようとすると、なぜかずっとプッシュできない、、
  ## どうやって解決した？
  いろいろ調べる中で**ある一定数以上の容量のファイルがあるとプッシュできない**ということが分かり、
```
Get-ChildItem "your-project-path" -Recurse | 
    Where-Object { $_.Length -ge 1048576 }
```
上記のコマンドを使うことによって一定数以上の容量を持つファイルを調べ、
**.gitignoreにファイル名を追加**これによってプッシュできるようになりました。


# ERデータベース図について
![スクリーンショット 2025-06-06 233529](https://github.com/user-attachments/assets/82e67e4d-9b50-4592-b267-609dd450e538)<br/>

| テーブル名 | 説明 |
| --- | --- |
| users | 登録ユーザ情報 |       
| admin | 管理者情報 |        
| products | 商品情報 |
| badges | セット用缶バッチ情報 |
| selected_badges | 購入されたセット用缶バッチ情報 |
| order_items | 購入された商品情報管理 |
| reviews | 商品レビュー情報 |
| product_user | お気に入り商品を登録するための中間テーブル |
<br/>
リレーションシップについてや外部キーについて実際に実装することでより詳しく学ぶことができました<br/>
# 本番環境図　
![スクリーンショット 2025-06-06 001343](https://github.com/user-attachments/assets/9fee2d12-45b3-46c8-a0ed-508eb1e10ce4)<br/>
クラウドからデプロイしてみたかったので一番一般的であろうAWSでデプロイを試みました。<br/>




