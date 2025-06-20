# PHP公式の軽量なFPMベースイメージを使用
FROM php:8.2-fpm

# 必要なPHP拡張とツールをインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath xml

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Node.jsインストール
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs
# 作業ディレクトリ
WORKDIR /var/www/html

# ホストとマウントするためCOPYは不要
# COPY . .

# ポート（PHP-FPMは直接ポート使わないのでこれは任意）
EXPOSE 9000

# CMDをフォアグラウンドモードで起動（-F）
CMD ["php-fpm", "-F"]

