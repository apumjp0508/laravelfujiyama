#!/bin/bash

echo "📦 Laravel 開発環境をビルド中..."

echo "🧨 以前の Docker 環境を完全に削除します（ボリューム含む）..."
docker-compose down -v


for container in laravel_mysql laravel_nginx laravel_app; do
  if [ "$(docker ps -aq -f name=$container)" ]; then
    echo "🧹 既存のコンテナ $container を削除します..."
    docker rm -f $container
  fi
done

# 1. イメージをビルドしてコンテナを起動
docker-compose up --build -d

docker-compose up -d app
docker-compose up -d db
sleep 5

echo "🚀 Nginx を起動します..."
docker-compose up -d nginx

# DB 起動を待つ
echo "⏳ MySQL の起動を待っています..."
sleep 5
docker-compose exec app php artisan migrate

docker-compose exec app php artisan storage:link

docker-compose exec app php artisan db:seed

echo "✅ Laravel 環境が起動しました！ http://localhost にアクセスできます。"
