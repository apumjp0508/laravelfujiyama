FROM ubuntu:22.04

# タイムゾーン設定と必要なパッケージのインストール
ENV TZ=UTC
RUN apt-get update && apt-get install -y \
    php \
    php-cli \
    php-mbstring \
    php-xml \
    php-bcmath \
    php-curl \
    php-mysql \
    php-zip \
    php-tokenizer \
    php-fileinfo \
    curl \
    unzip \
    git \
    zip \
    && apt-get clean

# Composer をインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 作業ディレクトリを作成
WORKDIR /var/www/html

# Laravel プロジェクトファイルをコピー（docker-compose側でマウントするなら不要）
COPY . .

# Composer install（初回ビルド時のみ。以降はボリューム上でやる方が良い）
RUN composer install

# Laravel のポート
EXPOSE 8000

# 起動時コマンド（任意：ここではLaravel開発サーバを起動）
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
