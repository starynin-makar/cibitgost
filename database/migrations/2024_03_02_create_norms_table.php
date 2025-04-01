<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('norms')) {
            Schema::create('norms', function (Blueprint $table) {
                $table->id();
                $table->string('number')->default('0');
                $table->text('content');
                $table->integer('status')->default(1);
                $table->text('comment')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('norms');
    }
}; 