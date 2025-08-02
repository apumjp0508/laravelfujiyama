<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            // 1. 新しいカラムを追加（同じ型）
            $table->unsignedBigInteger('product_set_id')->nullable();
        });

        // 2. 既存のbadge_idのデータを新しいカラムにコピー
        DB::statement('UPDATE before_buy_selected_badges SET product_set_id = badge_id');

        // 3. 外部キー制約を削除してから badge_id を削除
        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            $table->dropForeign(['badge_id']);
            $table->dropColumn('badge_id');
        });

        // 4. 新しい外部キー制約を追加
        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            $table->foreign('product_set_id')->references('id')->on('product_sets')->onDelete('cascade');
        });
    }

    public function down()
    {
        // 元に戻す処理（逆の順序で）
        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            $table->unsignedBigInteger('badge_id')->nullable();
        });

        DB::statement('UPDATE before_buy_selected_badges SET badge_id = product_set_id');

        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            $table->dropForeign(['product_set_id']);
            $table->dropColumn('product_set_id');
        });

        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            $table->foreign('badge_id')->references('id')->on('badges')->onDelete('cascade');
        });
    }
};
