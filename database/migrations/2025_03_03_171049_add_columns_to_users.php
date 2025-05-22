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
        Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'address')) {
        $table->text('address')->default('');
    }
    if (!Schema::hasColumn('users', 'postal_code')) {
        $table->string('postal_code')->default('');
    }
    if (!Schema::hasColumn('users', 'phone')) {
        $table->string('phone')->default('');
    }
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('postal_code')->default('');
            $table->text('address');
            $table->string('phone')->default('');
        });
    }
};
