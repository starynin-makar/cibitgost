<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Создаем временную таблицу для хранения копии данных
        DB::statement('CREATE TABLE IF NOT EXISTS norm_assessments_backup AS SELECT * FROM norm_assessments');
        
        // 2. Находим все записи, где явно кнопка "н/о" была нажата (имеются записи в БД)
        // Обновляем их score на -1
        DB::table('norm_assessments')
            ->whereRaw('score IS NULL')
            ->whereRaw('id IN (
                SELECT id FROM norm_assessments_backup 
                WHERE score IS NULL
            )')
            ->update(['score' => -1]);
            
        // 3. Другие записи пока оставляем как есть (null означает невыставленную оценку)
        
        // 4. Удаляем временную таблицу
        DB::statement('DROP TABLE IF EXISTS norm_assessments_backup');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем все -1 в NULL
        DB::table('norm_assessments')
            ->where('score', -1)
            ->update(['score' => null]);
    }
};
