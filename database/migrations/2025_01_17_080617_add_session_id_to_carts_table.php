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
            // Make user_id nullable since we can have guest carts
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('session_id');
            $table->unsignedBigInteger('user_id')->change();
        });
    }
};