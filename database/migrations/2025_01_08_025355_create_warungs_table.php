<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warungs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('location');
            $table->string('image');
            $table->string('open_hours');
            $table->string('distance');
            $table->decimal('rating', 2, 1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warungs');
    }
};