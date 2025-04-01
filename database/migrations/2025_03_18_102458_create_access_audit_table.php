<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('access_audit');
        
        Schema::create('access_audit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_id');
            $table->unsignedBigInteger('audit_id');
            $table->timestamps();

            $table->foreign('access_id')
                  ->references('id')
                  ->on('accesses')
                  ->onDelete('cascade');
                  
            $table->foreign('audit_id')
                  ->references('id')
                  ->on('audits')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('access_audit');
    }
};