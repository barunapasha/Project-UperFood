<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
            $table->index('session_id');
            // Buat user_id nullable karena kita ingin support guest cart
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('session_id');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};