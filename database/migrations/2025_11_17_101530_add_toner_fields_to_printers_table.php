<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            // Добавляем только то, чего точно нет
            if (!Schema::hasColumn('printers', 'toner_level')) {
                $table->integer('toner_level')->nullable()->after('model');
            }

            if (!Schema::hasColumn('printers', 'pages_count')) {
                $table->bigInteger('pages_count')->nullable()->after('toner_level');
            }

            if (!Schema::hasColumn('printers', 'last_check')) {
                $table->timestamp('last_check')->nullable()->after('pages_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn(['toner_level', 'pages_count', 'last_check']);
        });
    }
};