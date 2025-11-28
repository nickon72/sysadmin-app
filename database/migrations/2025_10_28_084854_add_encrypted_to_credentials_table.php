<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->boolean('encrypted')->default(false)->after('password');
        });
    }

    public function down()
    {
        Schema::table('credentials', function (Blueprint $table) {
            $table->dropColumn('encrypted');
        });
    }
};
