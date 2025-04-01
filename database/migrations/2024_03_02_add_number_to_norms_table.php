<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('norms', function (Blueprint $table) {
            $table->string('number')->default('0')->after('id');
        });
    }

    public function down()
    {
        Schema::table('norms', function (Blueprint $table) {
            $table->dropColumn('number');
        });
    }
}; 