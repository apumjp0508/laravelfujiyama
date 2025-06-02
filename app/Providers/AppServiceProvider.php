<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {/*⚠ しかし以下のケースでは http:// が混ざることがある：
CloudFront 経由でプロキシされたリクエスト

Laravel の Request::secure() が HTTPだと誤認する（実際は CloudFront→ALB→EC2 は HTTP 通信）

Nginx → Laravel にリダイレクト設定がない場合

Laravel の url() や asset() が http: プレフィックスでリンクを生成*/
        if (app()->environment('production')) {
        URL::forceScheme('https');
    }
        //
    }

 
}
