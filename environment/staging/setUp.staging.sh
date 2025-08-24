#!/usr/bin/env bash
set -euo pipefail

# =========================
# CoolMart Staging Setup
# =========================
# 目的:
#  - docker compose でステージング環境を起動/再起動
#  - 初回は .env.staging を生成（無ければ）
#  - マイグレーション/シーディング/キャッシュ最適化
#  - よく使うオプション (--rebuild, --fresh, --no-seed, --down, --purge, --logs)
#
# 使い方:
#   ./environment/staging/setUp.staging.sh
#   ./environment/staging/setUp.staging.sh --rebuild
#   ./environment/staging/setUp.staging.sh --fresh
#   ./environment/staging/setUp.staging.sh --no-seed
#   ./environment/staging/setUp.staging.sh --down
#   ./environment/staging/setUp.staging.sh --purge
#   ./environment/staging/setUp.staging.sh --logs
#
# 期待する構成:
#   - compose: environment/staging/docker-compose.staging.yml
#   - env:     environment/staging/.env.staging
#   - app svc: laravel_app_staging
#   - db  svc: laravel_mysql_staging
#   - web:     http://localhost:8080

# -------------------------
# 設定
# -------------------------
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/../.." && pwd)"
COMPOSE_FILE="${REPO_ROOT}/environment/staging/docker-compose.staging.yml"
ENV_FILE="${REPO_ROOT}/environment/staging/.env.staging"
APP_SVC="laravel_app_staging"
DB_SVC="laravel_mysql_staging"
NGINX_SVC="laravel_nginx_staging"
APP_URL_DEFAULT="http://localhost:8080"

# docker compose コマンド検出 (v1/v2 両対応)
if command -v docker &>/dev/null && docker compose version &>/dev/null; then
  DCMD=(docker compose)
elif command -v docker-compose &>/dev/null; then
  DCMD=(docker-compose)
else
  echo "❌ docker / docker compose が見つかりません。インストールしてください。" >&2
  exit 1
fi

# -------------------------
# オプション解析
# -------------------------
REBUILD=false
FRESH=false
NO_SEED=false
DO_DOWN=false
PURGE=false
SHOW_LOGS=false

for arg in "${@:-}"; do
  case "$arg" in
    --rebuild) REBUILD=true ;;
    --fresh)   FRESH=true ;;
    --no-seed) NO_SEED=true ;;
    --down)    DO_DOWN=true ;;
    --purge)   PURGE=true ;;
    --logs)    SHOW_LOGS=true ;;
    -h|--help)
      sed -n '1,100p' "$0" | sed -n '/^# =========================/,$p' | sed -n '1,80p' | sed 's/^# \{0,1\}//'
      exit 0
      ;;
    *)
      echo "⚠️ 不明なオプション: ${arg}" >&2
      ;;
  esac
done

# -------------------------
# ユーティリティ
# -------------------------
color() { printf "\033[%sm%s\033[0m\n" "$1" "$2"; }
info()  { color "1;34" "ℹ️  $*"; }
ok()    { color "1;32" "✅ $*"; }
warn()  { color "1;33" "⚠️  $*"; }
err()   { color "1;31" "❌ $*"; }

require_file() {
  local path="$1" desc="$2"
  [[ -f "$path" ]] || { err "${desc} が見つかりません: $path"; exit 1; }
}

exists() { command -v "$1" &>/dev/null; }

wait_for_db() {
  info "DB 起動待ち (${DB_SVC})..."
  # mysqladmin ping (コンテナ内) でヘルス確認
  for i in $(seq 1 60); do
    if docker ps --format '{{.Names}}' | grep -q "^${DB_SVC}$"; then
      if docker exec "${DB_SVC}" sh -c 'mysqladmin ping -h 127.0.0.1 -p"$MYSQL_PASSWORD" --silent' 2>/dev/null; then
        ok "DB 準備完了"
        return 0
      fi
    fi
    sleep 2
  done
  err "DB が起動しません。ログを確認してください: ${DB_SVC}"
  exit 1
}

run_artisan() {
  local cmd="$1"
  docker exec -i "${APP_SVC}" php artisan $cmd
}

# -------------------------
# 事前チェック & 初期化
# -------------------------
require_file "$COMPOSE_FILE" "docker-compose.staging.yml"

if [[ ! -f "$ENV_FILE" ]]; then
  warn ".env.staging が見つからないため初期テンプレートを作成します: $ENV_FILE"
  cat > "$ENV_FILE" <<EOF
APP_NAME=CoolMart
APP_ENV=staging
APP_DEBUG=false
APP_URL=${APP_URL_DEFAULT}
LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=${DB_SVC}
DB_PORT=3306
DB_DATABASE=coolmart_stg
DB_USERNAME=staging_user
DB_PASSWORD=staging_password

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@coolmart.stg
MAIL_FROM_NAME="CoolMart Staging"
EOF
  ok ".env.staging を生成しました"
fi

# -------------------------
# ダウン/パージ処理
# -------------------------
if $DO_DOWN; then
  info "compose を停止します..."
  "${DCMD[@]}" -f "$COMPOSE_FILE" down
  ok "停止しました"
  exit 0
fi

if $PURGE; then
  warn "compose を停止し、ボリュームも削除します（DBデータ消去）"
  "${DCMD[@]}" -f "$COMPOSE_FILE" down -v
  ok "停止 + ボリューム削除 完了"
  exit 0
fi

# -------------------------
# ビルド & 起動
# -------------------------
if $REBUILD; then
  info "イメージを再ビルドします..."
  "${DCMD[@]}" -f "$COMPOSE_FILE" build --no-cache
fi

info "compose を起動します..."
"${DCMD[@]}" -f "$COMPOSE_FILE" up -d

# -------------------------
# 健康チェック & 初期コマンド
# -------------------------
wait_for_db

info "Laravel アプリの初期化を行います..."
# アプリコンテナが上がるまで軽く待機
for i in $(seq 1 30); do
  if docker ps --format '{{.Names}}' | grep -q "^${APP_SVC}$"; then break; fi
  sleep 1
done

# アプリキー
if ! docker exec "${APP_SVC}" php -r 'exit((int) !file_exists("storage/framework/.app_key_ready"));'; then
  info "APP_KEY を生成します..."
  run_artisan "key:generate"
  docker exec "${APP_SVC}" sh -lc 'mkdir -p storage/framework && touch storage/framework/.app_key_ready'
fi

# マイグレーション
if $FRESH; then
  warn "migrate:fresh を実行します（DB初期化 + seed）"
  if $NO_SEED; then
    run_artisan "migrate:fresh --force"
  else
    run_artisan "migrate:fresh --seed --force"
  fi
else
  info "migrate を実行します"
  run_artisan "migrate --force" || true
  if ! $NO_SEED; then
    info "db:seed を実行します"
    run_artisan "db:seed --force" || true
  fi
fi

# キャッシュ類
info "キャッシュ最適化"
run_artisan "config:clear" || true
run_artisan "route:clear"  || true
run_artisan "config:cache" || true
run_artisan "route:cache"  || true

# 権限（必要に応じて）
docker exec "${APP_SVC}" sh -lc 'chown -R www-data:www-data storage bootstrap/cache || true'

ok "ステージング環境の起動・初期化が完了しました 🎉"
echo
echo "🔗 Access: ${APP_URL_DEFAULT}"
echo "📦 Compose: ${COMPOSE_FILE}"
echo

# -------------------------
# ログ表示（オプション）
# -------------------------
if $SHOW_LOGS; then
  info "Nginx と App のログをフォローします（Ctrl+Cで終了）"
  "${DCMD[@]}" -f "$COMPOSE_FILE" logs -f "${NGINX_SVC}" "${APP_SVC}"
fi
