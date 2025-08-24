# MY Original Market
初めて、アパレルECサイトを個人開発をしたので、紹介します。<br/>
![5JlVjL7s1WFMffbqDgBI1744782396-1744782437](https://github.com/user-attachments/assets/89e177d5-16e9-49da-98b1-c8115e46d291)
# 概要
ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>

運用したいと考えていますが、セキュリティーなどの様々なハードルがあり、まだ運用に至っていないです。
# デプロイ先
近日中にデプロイしたい、
# 制作背景
自分の服にすごい熱量を注いでいる友人がアクセサリ－を自作しており、それを販売したいとのことだったので、laravelを学習し始めたばかりの自分にとってすごくいい成長の機会だったため、ファッションアクセサリ－を主に販売するアパレルECサイトを作成しました。<br/>
また、このECサイトはプログラミングスクール侍のlaravelでAmazon風アプリを作ろうを参考にしています。<br/>
友人の方から、缶バッチをセット形式で販売したいため、数ある缶バッチの中から、4つ選んで購入できるようにしてほしいとの要望があり、それを実装しました。
# ECサイト　画面構成




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
