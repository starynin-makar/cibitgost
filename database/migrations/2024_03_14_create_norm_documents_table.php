<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('norm_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('norm_id')->constrained()->onDelete('cascade');
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->string('employee_name'); // ФИО и должность
            $table->string('source_type'); // Тип источника
            $table->text('results'); // Результаты опроса или наблюдений
            $table->string('file_path')->nullable(); // Путь к файлу
            $table->string('file_name')->nullable(); // Оригинальное имя файла
            $table->text('comment')->nullable(); // Комментарий
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('norm_documents');
    }
}; 