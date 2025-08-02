<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // 同じ型・nullableは旧定義に合わせて調整（ここでは JSON カラムを想定）
            $table->json('selected_product_sets')->nullable();
        });

        // データコピー（MySQLでもPostgreSQLでも対応）
        DB::statement('UPDATE order_items SET selected_product_sets = selected_badges');

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('selected_badges');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->json('selected_badges')->nullable();
        });

        DB::statement('UPDATE order_items SET selected_badges = selected_product_sets');

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('selected_product_sets');
        });
    }
};

