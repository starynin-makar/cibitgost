<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migration to support score value of 0.5
     * For SQLite we need to recreate the table as it doesn't support MODIFY directly
     */
    public function up()
    {
        // Создаем временную таблицу со всеми столбцами, но score как REAL
        Schema::create('norm_assessments_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->foreignId('norm_id')->constrained('norms')->cascadeOnDelete();
            $table->float('score', 3, 1)->nullable();  // Используем float вместо integer
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->text('evidence')->nullable();
            $table->text('notes')->nullable();
        });

        // Копируем данные из старой таблицы
        DB::statement('INSERT INTO norm_assessments_new (id, audit_id, norm_id, score, approved, created_at, updated_at, evidence, notes) 
                      SELECT id, audit_id, norm_id, score, approved, created_at, updated_at, evidence, notes FROM norm_assessments');

        // Удаляем старую таблицу
        Schema::dropIfExists('norm_assessments');

        // Переименовываем новую таблицу
        Schema::rename('norm_assessments_new', 'norm_assessments');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Создаем временную таблицу с исходной структурой (score как integer)
        Schema::create('norm_assessments_old', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->cascadeOnDelete();
            $table->foreignId('norm_id')->constrained('norms')->cascadeOnDelete();
            $table->integer('score')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->text('evidence')->nullable();
            $table->text('notes')->nullable();
        });

        // Копируем данные из текущей таблицы, при необходимости округляя значения
        DB::statement('INSERT INTO norm_assessments_old (id, audit_id, norm_id, score, approved, created_at, updated_at, evidence, notes) 
                      SELECT id, audit_id, norm_id, CAST(score AS INTEGER), approved, created_at, updated_at, evidence, notes FROM norm_assessments');

        // Удаляем текущую таблицу
        Schema::dropIfExists('norm_assessments');

        // Переименовываем старую таблицу
        Schema::rename('norm_assessments_old', 'norm_assessments');
    }
}; 