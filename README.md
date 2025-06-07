# MY Original Market
初めて、アパレルECサイトを個人開発をしたので、紹介します。<br/>
![5JlVjL7s1WFMffbqDgBI1744782396-1744782437](https://github.com/user-attachments/assets/89e177d5-16e9-49da-98b1-c8115e46d291)
# 概要
ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>
まだ、未完成な部分があるが、このアプリの概要を示したいため、READMEを制作。

# 制作背景
自分の服にすごい熱量を注いでいる友人がアクセサリ－を自作しており、それを販売したいとのことだったので、laravelを学習し始めたばかりの自分にとってすごくいい成長の機会だったため、ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>
また、このECサイトはプログラミングスクール侍のlaravelでAmazon風アプリを作ろうを参考にしています。<br/>
友人の方から、缶バッチをセット形式で販売したいため、数ある缶バッチの中から、4つ選んで購入できるようにしてほしいとの要望があり、それを実装しました。
# ECサイト　画面遷移図
![スクリーンショット 2025-06-05 205829](https://github.com/user-attachments/assets/9c399c16-03a1-4afc-9d9a-de771f653d27)<br/>
管理者用ページや顧客用ページを分けることでmiddlewareの概念やrouteとの違いについて学ぶことができました！
# 開発環境について
## 使用技術
### フロントエンド
- HTML
- CSS
- Bootstrap
- Javascript
- jQeury
### バックエンド
- PHP 8.2.12
- Laravel 9.52.20
### 環境
- Git
- GitHub
- Composer 2.8.8
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
今回はポートフォリオ用サイトである点からコスト面を考えシンプルな構造で構成しました。<br/>
一からクラウドとはなにかについてや各サービスについての概要、サービス同士のつながり方、実装方法などを学び始めたため、構築までにかなりの時間を要しており今で完全にデプロイできたといえる状況ではありません。<br/>

## デプロイ状況
1. EC2インスタンス(amazon linux2)を立ち上げる
2. EC2インスタンスとSSH接続をして、dockerコンテナと接続、コンテナをビルドし、起動する、
3. お名前.comでcoolmart.jpを取得
4. ACMでSSL証明書発行、HTTPS化
5. Route53でレコード発行
6. cloudfrontでディストリビューション作成
7. ターゲットグループを作成し、ALBと接続、RDS導入
8. RDS導入
9. S3導入
**現在**→→laravelアプリでアップロードしS3に保存した画像を表示することができない、、<br/>
このような感じで自分の本番環境構築についてかなり大雑把に説明しました。本番環境構築での生じた問題等については、今回は割愛させていただきます。

# 搭載した機能

### 顧客側機能 
1. 新機会員登録、ログイン機能、会員情報編集、ログアウト機能
2. 商品お気に入り登録、お気に入り商品確認
3. 商品レビューを書く
4. 商品検索機能、商品カテゴリー検索
5. セット用商品購入、（複数ある商品から、ある一定数個選んで購入することが可能）
6. 購入商品数量選択(非同期通信）
7. 商品カートに追加、カート確認、カート内で購入商品編集
8. 支払い(stripe等の決済サービスは未実装）

### 管理者側機能
1. 商品情報追加、編集、削除
2. レビュー編集、削除
3. セット用缶バッチ追加、編集、削除
4. 購入された商品情報確認

# 開発で苦労した点
## カートの中身が削除できない
ショッピングカート機能をbumbummen99/shoppingcartをインストールすることで実装し、カートの中身を削除する機能をつけようと試みたところ、ずっとエラー続きでした。<br/>

**原因**<br/>
```
public function destroy($rowId)
    {
        Cart::instance();

        Cart::remove($rowId);
        return back();
    }
```
という機能で実装していました。同時の自分の考えではCartはファサードだからインスタンス化しなくても使えるものという考えが邪魔してどのセッションでのカートかという情報が渡せません。
これじゃあエラーが出て当然です(笑)それに気づきこのようにコードを修正して解決しました。
```
public function destroy($rowId)
    {
        Cart::instance(Auth::user()->id);

        Cart::remove($rowId);
        return back();
    }
```
## セット用缶バッチ購入でのデータベース構造
実装したいと思っている機能は数ある商品の中から複数個（今回は4つ）選んで購入できるようにするセット商品を販売するものです。<br/>
当初は購入したセット用商品の中身の情報（どの缶バッチを選択した）を中間テーブルに保存しようと思っていました。その中間テーブルの詳細はこんな感じです↓<br/>
|カラム名 |
| --- |
| product_id |
| user_id |
| badge_id |
<br/>
これだけで誰がセット商品を購入してどの商品を選んだかわかると思って最初は進めていきましたが、途中で同じユーザーが複数セット商品を購入した場合、セット商品ごとの中身の詳細がわからなくなるというものでした。この上のテーブルだと、商品ごとのIDがないため識別することはできません（product_id）は同じ商品種類だった場合、それらを識別することはできない。<br/>

**解決方法**<br/>
セットの中身の商品を選択する際、選んだセット商品の中身の商品IDを配列で以下のようにカートにいれそのまま、配列としてJson形式で保存することで解決しました。
```
 $selectedBadges = $request->input('select', []); 
        $productId=$request->input('product_id');
        $userId=$request->input('user_id');
        $setId = Str::uuid();

        // 選択したバッジを保存する（例: `selected_badges` テーブルに保存）
        foreach ($selectedBadges as $badgeId) {
            SelectedBadge::create([
                'set_id'=>$setId,
                'badge_id' => $badgeId,
                'product_id'=>$productId,
                'user_id'=>$userId,
                'widthSize'=>$width,
                'heightSize'=>$height
            ]);
        }
```
## カート内商品数量を非同期通信で変更する機能
カート内の商品数量を非同期通信で変更する機能を作りたいなと思い実装しようと試みましたがエラー続きでなかなかうまくいきませんでした。そのときのコードはこちらでした↓↓<br/>
```
$(document).ready(function () {
    $('number-change').on('click', function () {
        let qty = $('input[name="qty"]').val();
        let productId = $('input[name="qty"]').closest('form').find('input[name="product_id"]').val();
        let token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '/cart/update',  // ← ここが問題ポイント！！！
            type: "POST",
            data: {
                _token: token,
                product_id: productId,
                qty: qty
            },
```
この上のコードだとurlの部分にサーバーに処理をお願いするためにurlを同封するはずが url: '/cart/update',が文字列として認識されてしまい、うまく非同期通信することができませんでした。<br/>
これを以下のように改善することで正しい処理ができるようになりました。
```
$(document).ready(function () {
    let updateUrl = $('#cart-update-url').val(); // ここでルートを取得

    $('number-change').on('click', function () {
        let qty = $('input[name="qty"]').val();
        let productId = $('input[name="qty"]').closest('form').find('input[name="product_id"]').val();
        let token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: updateUrl,  // ここが修正ポイントそのまま最初は文字列を埋め込んでいたからエラーになったらしい
            type: "POST",
            data: {
                _token: token,
                product_id: productId,
                qty: qty
            },
```
このようにルートを外部から所得してきて、それを利用することで改善しました。<br/>
非同期通信は他の部分でも実装しており、どれも苦戦が強いられましたが、これが一番苦戦したためこちらをご紹介させていただきました。

# 課題点
1. **セット用商品について**
   今の状況だと、セット用商品を登録し、いざ購入してもらおうとなると、登録した、セット用商品の中身の商品がすべて表示されてしまうことになってしまうため、セットによってどの商品を表示するかを選択することができるようにしようと思います。
2. **テスト実装がかなり雑**
   これはかなり重大な課題点であると思う、開発の工程を1つすっ飛ばしているようなものだからです。今回のテストは自分含め友人等に実際にアプリを触ってもらい、エラーがないか確かめてもらった、
3. **レスポンシブデザインが雑**
   実際の運用を考えていなかったため、laravel、aws周りを特に理解しながら進めていったため、ココはおろそかになっていいたため、次はちゅんとこだわりたいです。
4. **命名法が雑**
   コミットコメントや関数名など命名法において雑な命名が多かったので、このままでは現場に入ったときに迷惑をかけてしまうので注意したい

## 最後に
初めてlaravelを学びアプリ開発を行ったため右も左もわからない状態であったため、気づいていないミスがあるかもしれないので有識者の方でも誰でもご指摘お願いします。











