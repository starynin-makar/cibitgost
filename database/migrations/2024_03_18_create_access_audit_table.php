<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
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
    }
}; 