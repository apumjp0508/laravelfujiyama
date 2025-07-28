<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // 外部キー制約がある場合は削除（なければこの行は削除可）
            // $table->dropForeign(['order_id']);

            $table->dropColumn('order_id'); // 一旦削除
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('id'); // nullableで再追加
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('order_id'); // 取り消し時も削除

            $table->unsignedBigInteger('order_id')->nullable(false)->after('id'); // 非nullableで復元
        });
    }
};

