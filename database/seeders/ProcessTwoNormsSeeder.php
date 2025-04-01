<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessTwoNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 2)->delete();

        $process = 'Процесс 2 "Обеспечение защиты вычислительных сетей"';
        $subprocess = 'Подпроцесс "Обеспечение защиты вычислительных сетей"';

        $norms = [
            [
                'code' => 'ВС.1',
                'description' => 'Обеспечение защиты вычислительных сетей',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 2,
                'order' => 100
            ],
            [
                'code' => 'ВС.2',
                'description' => 'Обеспечение защиты периметра вычислительных сетей',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 2,
                'order' => 101
            ],
            [
                'code' => 'ВС.3',
                'description' => 'Обеспечение защиты от несанкционированного доступа к сетевым устройствам',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 2,
                'order' => 102
            ],
            [
                'code' => 'ВС.4',
                'description' => 'Обеспечение защиты от несанкционированного доступа к сетевому трафику',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 2,
                'order' => 103
            ],
            [
                'code' => 'ВС.5',
                'description' => 'Контроль сетевых соединений',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 2,
                'order' => 104
            ],
            [
                'code' => 'ВС.6',
                'description' => 'Защита беспроводных сетей',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 2,
                'order' => 105
            ],
            [
                'code' => 'ВС.7',
                'description' => 'Сегментация вычислительных сетей',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 2,
                'order' => 106
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 