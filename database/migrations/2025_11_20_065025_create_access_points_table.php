<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('access_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // например "Точка приёмная"
            $table->string('mac_address')->unique();   // 1C-3B-F3-48-B3-D6
            $table->ipAddress('ip');                     // 192.168.13.131
            $table->string('model')->nullable();
            $table->string('firmware')->nullable();
            $table->integer('uptime_seconds')->nullable(); // в секундах
            $table->string('status')->default('unknown');  // connected / disconnected
            $table->timestamp('last_check')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_points');
    }
};