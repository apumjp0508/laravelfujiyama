<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('product_name')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('total_price')->nullable()->after('user_id');
            $table->string('statusItem')->nullable()->after('total_price');
            $table->string('productType')->nullable()->after('statusItem');
            $table->json('selected_badges')->nullable()->after('productType');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'user_id',
                'total_price',
                'statusItem',
                'productType',
                'selected_badges',
            ]);
        });
    }
};
