<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessSevenNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 7)->delete();

        $process = 'Процесс 7 "Защита среды виртуализации"';
        $subprocess = 'Подпроцесс "Защита среды виртуализации"';

        $norms = [
            [
                'code' => 'ЗСВ.1',
                'description' => 'Разграничение и контроль осуществления одновременного доступа к виртуальным машинам с АРМ пользователей и эксплуатационного персонала только в пределах одного контура безопасности',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 7,
                'order' => 289
            ],
            [
                'code' => 'ЗСВ.2', 
                'description' => 'Разграничение и контроль осуществления одновременного доступа к виртуальным машинам с АРМ пользователей и эксплуатационного персонала только в пределах одного контура безопасности на уровне не выше третьего (сетевой) по семиуровневой стандартной модели взаимодействия открытых систем, определенной в ГОСТ Р ИСО/МЭК 7498-1',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 7,
                'order' => 290
            ],
            [
                'code' => 'ЗСВ.3',
                'description' => 'Разграничение и контроль осуществления одновременного доступа виртуальных машин к системе хранения данных в пределах контура безопасности',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 7,
                'order' => 291
            ],
            // ... добавьте остальные нормы аналогично
            [
                'code' => 'ЗСВ.43',
                'description' => 'Регистрация операций, связанных с изменением настроек технических мер защиты информации, используемых для обеспечения защиты виртуальных машин',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 7,
                'order' => 331
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 