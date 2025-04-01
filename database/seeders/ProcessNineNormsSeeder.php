<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessNineNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 9)->delete();

        $process = 'Процесс 9 "Оценка соответствия ЗИ на этапах жизненного цикла АС"';
        $subprocess = 'Подпроцесс "Оценка соответствия ЗИ на этапах жизненного цикла АС"';

        $norms = [
            [
                'code' => 'ЖЦ.1',
                'description' => 'Документарное определение перечня защищаемой информации, планируемой к обработке в АС',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 9,
                'order' => 640
            ],
            [
                'code' => 'ЖЦ.2',
                'description' => 'Документарное определение состава (с указанием соответствия настоящему стандарту) и содержания мер системы защиты информации АС (функционально-технических требований к системе защиты информации АС)',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 9,
                'order' => 641
            ],
            [
                'code' => 'ЖЦ.3',
                'description' => 'Документарное определение в проектной и эксплуатационной документации на систему защиты информации АС: - состава и порядка применения технических и (или) организационных мер системы защиты информации АС; - параметров настроек технических мер системы защиты информации АС и компонентов информационной инфраструктуры, предназначенных для размещения указанных технических мер*',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 9,
                'order' => 642
            ],
            [
                'code' => 'ЖЦ.27',
                'description' => 'Обеспечение защиты резервных копий защищаемой информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 9,
                'order' => 666
            ],
            [
                'code' => 'ЖЦ.28',
                'description' => 'Реализация контроля уничтожения защищаемой информации в случаях, когда указанная информация больше не используется, в том числе содержащейся в архивах, с применением мер ПУИ.23-ПУИ.26 таблицы 31',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 9,
                'order' => 667
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 