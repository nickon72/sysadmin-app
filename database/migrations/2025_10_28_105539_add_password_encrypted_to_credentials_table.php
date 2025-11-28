<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->text('password_encrypted')->nullable()->after('password');
        });

        // Переносим старые пароли в зашифрованный столбец
        \DB::statement("UPDATE credentials SET password_encrypted = password");
        \DB::statement("UPDATE credentials SET password = '******' WHERE password IS NOT NULL");
    }

    public function down()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn('password_encrypted');
        });
    }
};
