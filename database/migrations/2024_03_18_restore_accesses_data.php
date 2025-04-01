<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Восстанавливаем данные из предыдущей миграции
        if (property_exists($this, 'data') && !empty($this->data)) {
            foreach ($this->data as $item) {
                DB::table('accesses')->insert($item);
            }
        }

        // Восстанавливаем связи
        if (property_exists($this, 'audit_relations') && !empty($this->audit_relations)) {
            foreach ($this->audit_relations as $relation) {
                DB::table('access_audit')->insert((array)$relation);
            }
        }
    }

    public function down()
    {
        // Не нужно ничего делать при откате
    }
}; 