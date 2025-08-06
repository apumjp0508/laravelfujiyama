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
        // Repository bindings
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\ProductRepositoryInterface::class,
            \App\Repositories\ProductRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\OrderItemRepositoryInterface::class,
            \App\Repositories\OrderItemRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\ProductSetRepositoryInterface::class,
            \App\Repositories\ProductSetRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\ReviewRepositoryInterface::class,
            \App\Repositories\ReviewRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\AdminRepositoryInterface::class,
            \App\Repositories\AdminRepository::class
        );
        
        $this->app->bind(
            \App\Repositories\Contracts\BeforeBuySelectedProductSetRepositoryInterface::class,
            \App\Repositories\BeforeBuySelectedProductSetRepository::class
        );
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
