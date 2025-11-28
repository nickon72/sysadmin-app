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
    Schema::create('computers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('ip')->unique();
        $table->string('employee_name')->nullable(); // Владелец
        $table->string('location')->nullable(); // Кабинет
        $table->string('status')->default('unknown'); // online/offline
        $table->integer('ping_time')->nullable(); // мс
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('computers');
}

};
