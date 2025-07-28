<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->integer('widthSize')->nullable()->after('id');   // 適切な位置に変更可
            $table->integer('heightSize')->nullable()->after('widthSize');
        });
    }

    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->dropColumn(['widthSize', 'heightSize']);
        });
    }
};
