<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->integer('number');
            $table->string('name');
            $table->string('technical_score')->default('н/о');
            $table->string('planning_score')->default('н/о');
            $table->string('implementation_score')->default('н/о');
            $table->string('control_score')->default('н/о');
            $table->string('improvement_score')->default('н/о');
            $table->string('qualitative_score')->default('н/о');
            $table->string('numerical_score')->default('н/о');
            $table->integer('violations_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('processes');
    }
}; 