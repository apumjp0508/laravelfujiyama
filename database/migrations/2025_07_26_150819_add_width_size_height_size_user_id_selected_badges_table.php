<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            $table->integer('widthSize')->nullable()->after('id');
            $table->integer('heightSize')->nullable()->after('widthSize');
            $table->unsignedBigInteger('user_id')->nullable()->after('heightSize');

            // 外部キー制約を付けたい場合（users テーブルがある前提）
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('before_buy_selected_badges', function (Blueprint $table) {
            // 外部キー制約を使っている場合は先に削除
            // $table->dropForeign(['user_id']);
            $table->dropColumn(['widthSize', 'heightSize', 'user_id']);
        });
    }
};

