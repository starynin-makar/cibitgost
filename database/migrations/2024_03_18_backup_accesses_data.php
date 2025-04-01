<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Сохраняем данные во временную переменную
        $this->data = DB::table('accesses')->get()->map(function($item) {
            return [
                'user_id' => $item->user_id,
                'organization_id' => $item->organization_id,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
        })->toArray();

        // Сохраняем связи access_audit
        $this->audit_relations = DB::table('access_audit')->get()->toArray();
    }

    public function down()
    {
        // Не нужно ничего делать при откате
    }

    protected $data = [];
    protected $audit_relations = [];
}; 