<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessFourteenNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 14)->delete();

        $process = 'Процесс 14 "Итоговые оценки"';
        $subprocess = 'Подпроцесс "Итоговые оценки"';

        $norms = [
            [
                'code' => 'ИО.1',
                'description' => 'Процесс 1 "Обеспечение защиты информации при управлении доступом"',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 700
            ],
            [
                'code' => 'ИО.2',
                'description' => 'Процесс 2 "Обеспечение защиты вычислительных сетей"',
                'implementation_type' => 'О', 
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 701
            ],
            [
                'code' => 'ИО.3',
                'description' => 'Процесс 3 "Контроль целостности и защищенности информационной инфраструктуры"',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 702
            ],
            [
                'code' => 'ИО.4',
                'description' => 'Процесс 4 "Защита от вредоносного кода"',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 703
            ],
            [
                'code' => 'ИО.5',
                'description' => 'Процесс 5 "Предотвращение утечек информации"',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 704
            ],
            [
                'code' => 'ИО.6',
                'description' => 'Процесс 6 "Управление инцидентами защиты информации"',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 705
            ],
            [
                'code' => 'ИО.7',
                'description' => 'Процесс 7 "Защита среды виртуализации"',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 706
            ],
            [
                'code' => 'ИО.8',
                'description' => 'Процесс 8 "Защита информации при осуществлении удаленного логического доступа с использованием мобильных (переносных) устройств"',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 707
            ],
            [
                'code' => 'ИО.9',
                'description' => 'Применение организационных и технических мер ЗИ на этапах жизненного цикла АС',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 708
            ],
            [
                'code' => 'ИО.10',
                'description' => 'Количество нарушений ЗИ, выявленных в результате оценки соответствия ЗИ',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 709
            ],
            [
                'code' => 'ИО.11',
                'description' => 'Итоговая оценка соответствия ЗИ',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 14,
                'order' => 710
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 