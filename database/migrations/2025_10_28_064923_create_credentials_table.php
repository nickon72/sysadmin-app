<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('credentials', function (Blueprint $table) {
        $table->id();
        $table->string('service_name');
        $table->string('login');
        $table->string('password');
        $table->string('connection_method')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
