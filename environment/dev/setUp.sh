#!/bin/bash

# プロジェクトルートの絶対パスを取得
ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
DOCKER_COMPOSE_FILE="$ROOT_DIR/environment/dev/docker-compose.yml"
ENV_FILE="$ROOT_DIR/environment/dev/.env"

echo "DEBUG: ENV_FILE=$ENV_FILE"
echo "DEBUG: DOCKER_COMPOSE_FILE=$DOCKER_COMPOSE_FILE"


echo "📦 Laravel 開発環境をビルド中..."

echo "📄 .env ファイルをコピーしています..."
cp "$ENV_FILE" "$ROOT_DIR/.env"

echo "🧨 以前の Docker 環境を完全に削除します（ボリューム含む）..."
docker-compose -f "$DOCKER_COMPOSE_FILE" down -v

for container in laravel_mysql laravel_nginx laravel_app; do
  if [ "$(docker ps -aq -f name=$container)" ]; then
    echo "🧹 既存のコンテナ $container を削除します..."
    docker rm -f $container
  fi
done

echo "🔨 コンテナをビルドして起動します..."
docker-compose -f "$DOCKER_COMPOSE_FILE" up --build -d

sleep 5
echo "🚀 Nginx を起動します..."
docker-compose -f "$DOCKER_COMPOSE_FILE" up -d nginx

echo "⏳ MySQL の起動を待っています..."
for i in {1..30}; do
  if docker exec laravel_mysql mysqladmin ping -h "127.0.0.1" --silent 2>/dev/null; then
    echo "   ✅ MySQL が起動しました"
    break
  fi
  echo "   ↪️ MySQL 起動待機中... ($i/30)"
  sleep 2
done

if ! docker exec laravel_mysql mysqladmin ping -h "127.0.0.1" --silent 2>/dev/null; then
  echo "   ❌ MySQL の起動がタイムアウトしました"
  exit 1
fi

echo "🔧 Laravel の初期コマンドを実行しています..."
echo "   📋 マイグレーション実行中..."
docker-compose -f "$DOCKER_COMPOSE_FILE" exec app php artisan migrate --force

echo "   🔗 ストレージリンク作成中..."
docker-compose -f "$DOCKER_COMPOSE_FILE" exec app php artisan storage:link

echo "   🌱 シーダー実行中..."
docker-compose -f "$DOCKER_COMPOSE_FILE" exec app php artisan db:seed --force

CONTAINER_NAME=laravel_mysql
MYSQL_USER=root
MYSQL_PASSWORD=password

echo "🗃️ テストデータベースを作成中..."
docker exec $CONTAINER_NAME mysql -u$MYSQL_USER -p$MYSQL_PASSWORD -e "CREATE DATABASE IF NOT EXISTS testing"

echo "✅ Laravel 環境が起動しました！ http://localhost にアクセスできます。"
