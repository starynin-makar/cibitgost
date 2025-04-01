<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Обновляем существующие оценки в таблице norm_assessments
        DB::table('norm_assessments')
            ->where('score', 0)
            ->update(['score' => 0.5]);

        // Обновляем тип колонки score на decimal для поддержки значений 0.5
        Schema::table('norm_assessments', function (Blueprint $table) {
            $table->decimal('score', 3, 1)->change();
        });
    }

    public function down()
    {
        // Возвращаем оценки обратно к 0
        DB::table('norm_assessments')
            ->where('score', 0.5)
            ->update(['score' => 0]);

        // Возвращаем тип колонки score обратно на integer
        Schema::table('norm_assessments', function (Blueprint $table) {
            $table->integer('score')->change();
        });
    }
}; 