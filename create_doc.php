<?php

// Автозагрузка Laravel
require __DIR__.'/vendor/autoload.php';

// Загрузка конфигурации Laravel из .env
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\NormDocument;

// Удаляем существующие документы для аудита ID 11
$deleted = NormDocument::where('audit_id', 11)->delete();
echo "Удалено документов: {$deleted}\n";

// Создаем новый документ
$doc = new NormDocument();
$doc->audit_id = 11;
$doc->norm_id = 1;
$doc->file_path = 'documents/test.txt';
$doc->uploaded_by = 1;
$doc->employee_name = 'Иванов И.И.';
$doc->source_type = 'upload';
$doc->results = 'Тестовые результаты';
$doc->responsible_person = 'Тестовый отвественный';
$doc->version = '1.0';
$doc->save();

echo "Документ создан с ID: {$doc->id}\n";

// Еще несколько документов для полноты теста
for ($i = 0; $i < 2; $i++) {
    $doc = new NormDocument();
    $doc->audit_id = 11;
    $doc->norm_id = $i + 2;
    $doc->file_path = 'documents/test.txt';
    $doc->uploaded_by = 1;
    $doc->employee_name = 'Петров П.П.';
    $doc->source_type = 'upload';
    $doc->results = 'Тестовые результаты ' . ($i + 1);
    $doc->responsible_person = 'Тестовый отвественный ' . ($i + 1);
    $doc->version = '1.' . $i;
    $doc->save();
    
    echo "Дополнительный документ создан с ID: {$doc->id}\n";
}

echo "Всего документов для аудита 11: " . NormDocument::where('audit_id', 11)->count() . "\n"; 