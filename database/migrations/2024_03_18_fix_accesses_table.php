<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Удаляем старую таблицу
        Schema::dropIfExists('access_audit');
        Schema::dropIfExists('accesses');
        
        // Создаем новую таблицу без поля email
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'organization_id']);
        });

        // Создаем таблицу связей
        Schema::create('access_audit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('access_id')->constrained('accesses')->onDelete('cascade');
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['access_id', 'audit_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_audit');
        Schema::dropIfExists('accesses');
    }
}; 