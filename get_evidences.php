<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
$app = app();
$app->boot();

// Получить структуру таблицы
$columns = Schema::getColumnListing('audit_evidences');
echo "Columns in audit_evidences table:\n";
print_r($columns);

// Получить первые 5 записей
$evidences = DB::table('audit_evidences')->take(5)->get();
echo "\nFirst 5 records from audit_evidences:\n";
print_r($evidences);

// Получить количество записей для аудита с ID 11
$count = DB::table('audit_evidences')->where('audit_id', 11)->count();
echo "\nNumber of records for audit_id 11: " . $count . "\n";

// Если есть записи для аудита 11, получить их
if ($count > 0) {
    $audit11_evidences = DB::table('audit_evidences')->where('audit_id', 11)->take(3)->get();
    echo "\nRecords for audit_id 11:\n";
    print_r($audit11_evidences);
}

// Проверить наличие записей в таблице norm_documents
$norm_docs = DB::table('norm_documents')->where('audit_id', 11)->take(3)->get();
echo "\nNorm documents for audit_id 11:\n";
print_r($norm_docs); 