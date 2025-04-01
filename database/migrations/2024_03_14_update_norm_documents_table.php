<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('norm_documents', function (Blueprint $table) {
            $table->string('responsible_person')->nullable();
            $table->string('version')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users');
            $table->timestamp('uploaded_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('norm_documents', function (Blueprint $table) {
            $table->dropColumn(['responsible_person', 'version', 'uploaded_by', 'uploaded_at']);
        });
    }
}; 