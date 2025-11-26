# MY Original Market
初めて、アパレルECサイトを個人開発をしたので、紹介します。<br/>
![dsGhNI0ZYYpbV1lJRuKV1764146605-1764146643](https://github.com/user-attachments/assets/d962d6b7-1f53-4158-9b8a-fd0aae026e34)

# 概要
ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>

# 制作背景
自分の服にすごい熱量を注いでいる友人がアクセサリ－を自作しており、それを販売したいとのことだったので、laravelを学習し始めたばかりの自分にとってすごくいい成長の機会だったため、ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>
また、このECサイトはプログラミングスクール侍のlaravelでAmazon風アプリを作ろうを参考にしています。<br/>
友人の方から、缶バッチをセット形式で販売したいため、数ある缶バッチの中から、4つ選んで購入できるようにしてほしいとの要望があり、それを実装しました。
## 技術スタック

### バックエンド

| 技術 | バージョン | 用途 |
|------|-------------|------|
| PHP | 8.2 | メイン言語 |
| Laravel | 9.x | フレームワーク |
| Laravel Sanctum | 3.0 | API認証 |
| Laravel Breeze | * | 認証スターターキット |
| Stripe SDK | 17.4 | 決済処理 |
| Shopping Cart | 4.2 | カート機能 (`bumbummen99/shoppingcart`) |
| Doctrine DBAL | 4.3 | データベース抽象化 |

---

### フロントエンド

| 技術 | バージョン | 用途 |
|------|-------------|------|
| Blade | - | テンプレートエンジン |
| Tailwind CSS | 3.1 | CSSフレームワーク |
| Alpine.js | 3.4 | 軽量JSフレームワーク |
| Vite | 4.0 | ビルドツール |
| Axios | 1.1 | HTTPクライアント |
| Bootstrap | - | UI（一部使用） |
| jQuery | - | DOM操作（一部使用） |

---

### データベース

| 技術 | バージョン | 用途 |
|------|-------------|------|
| MySQL | 8.0 | データベース |

## ステージングインフラ環境
### ① 前提準備（ローカル・GitHub）

- [ ]  `laravel-coolmart` リポジトリに `staging` ブランチを作成
- [ ]  `.env.staging` の環境変数を確認・整備（DB接続、APP_ENV=staging、APP_URL など）
- [ ]  `docker-compose.staging.yml` が正しく Laravel + DB + その他サービス を構成しているか確認
- [ ]  GitHub Actions 用 `.github/workflows/deploy-staging.yml` を作成する準備

---

### ② AWSインフラ準備（IaCも可）

### Aurora Serverless（MySQL/PostgreSQL）

- [ ]  DB作成（マネジメントコンソール or CloudFormation）
- [ ]  接続ユーザー／パスワード／セキュリティグループを `.env.staging` に反映

### Cognito（または Firebase Auth）

- [ ]  ユーザープール作成
- [ ]  App Client 設定
- [ ]  認証用ドメイン設定（任意）
- [ ]  `.env.staging` に必要な認証情報を反映

### Lambda + API Gateway（Laravelデプロイ用）

- [ ]  Laravel アプリを Lambda にデプロイ（例: `bref` や `laravel-vapor`）
    - `bref` の場合は `serverless.yml` を作成
- [ ]  API Gateway 経由でエンドポイント発行
- [ ]  カスタムドメイン（Route 53）と ACM の設定

### S3 + CloudFront（フロント側）

- [ ]  SPA or 静的アセットをビルド → S3に配置（`npm run build` など）
- [ ]  CloudFront を通して公開
- [ ]  ACM（SSL証明書）設定
- [ ]  `example.com` ドメインを Route53 経由で設定し、S3 + CloudFront と紐付け

---

### ③ CI/CDパイプライン構築（GitHub Actions）

- [ ]  ブランチが `staging` に pushされたら以下を実行：
    - Laravelのユニットテスト／静的解析
    - Lambdaへ自動デプロイ（`serverless deploy` など）
    - S3へのビルド済み静的ファイルアップロード（SPA用）
    - デプロイ結果を SlackやDiscordに通知（任意）

---

### ④ デプロイ後の設定

- [ ]  CloudWatch Logs にて Lambda のログ確認
- [ ]  API Gateway のステージモニタリング有効化
- [ ]  Laravelの `.env.staging` に `LOG_CHANNEL=stderr` などログ出力先を設定

---

### ⑤ 動作確認・ステージング用URL配布

- [ ]  `https://staging.example.com` にアクセスして表示確認
- [ ]  フロント→API→DB まで一連の流れをチェック
- [ ]  認証フロー（ログイン、ログアウト、新規登録）確認



# CoolMart Staging – Phase 1 Decision Sheet

[DB]
- Engine: Aurora MySQL v3 (MySQL 8.0)
- Capacity: 0.5 ~ 1.0 ACU
- Charset/Collation: utf8mb4 / utf8mb4_0900_ai_ci
- SQL_MODE: default (ONLY_FULL_GROUP_BY kept)
- Timezone: Asia/Tokyo (App=UTC)

[VPC]
- NAT: none (use VPC endpoints for ssm/kms)
- Subnets: PrivateA / PrivateC
- SG: sg-cm-stg-aurora (in 3306 from sg-cm-stg-lambda), sg-cm-stg-lambda (egress allow)

[Naming]
- Cluster: aurora-cm-stg
- DB name: coolmart_stg
- Admin: cmart_admin

[Secrets via SSM]
- /coolmart/staging/DB_PASSWORD
- /coolmart/staging/MAILTRAP_USER
- /coolmart/staging/MAILTRAP_PASS

[Domains]
- Front: https://staging.example.com
- API:   https://api-staging.example.com
