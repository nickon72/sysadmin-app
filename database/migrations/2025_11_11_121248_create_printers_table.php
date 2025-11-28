<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip')->unique();
            $table->string('model')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('printers');
    }
};