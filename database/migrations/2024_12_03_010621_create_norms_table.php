<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNormsTable extends Migration
{
    public function up()
    {
        Schema::create('norms', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->text('description');
            $table->string('implementation_type');
            $table->string('process_name');
            $table->string('subprocess_name');
            $table->integer('tab')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('norms');
    }
}
