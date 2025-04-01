<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessSixNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 6)->delete();

        $process = 'Процесс 6 "Управление инцидентами защиты информации"';
        $subprocess1 = 'Подпроцесс "Мониторинг и анализ событий защиты информации"';
        $subprocess2 = 'Подпроцесс "Обнаружение и реагирование на инциденты защиты информации"';

        $norms = [
            [
                'code' => 'МАС.1',
                'description' => 'Организация мониторинга данных регистрации о событиях защиты информации, формируемых техническими мерами, входящими в состав системы защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 6,
                'order' => 247
            ],
            [
                'code' => 'МАС.2',
                'description' => 'Организация мониторинга данных регистрации о событиях защиты информации, формируемых сетевым оборудованием, в том числе активным сетевым оборудованием, маршрутизаторами, коммутаторами',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 6,
                'order' => 248
            ],
            [
                'code' => 'МАС.3',
                'description' => 'Организация мониторинга данных регистрации о событиях защиты информации, формируемых сетевыми приложениями и сервисами',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 6,
                'order' => 249
            ],
            // ... остальные нормы для подпроцесса 1
            [
                'code' => 'РИ.5',
                'description' => 'Установление и применение единых правил регистрации и классификации инцидентов защиты информации в части состава и содержания атрибутов, описывающих инцидент защиты информации, и их возможных значений',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 6,
                'order' => 274
            ],
            // ... остальные нормы для подпроцесса 2
            [
                'code' => 'РИ.19',
                'description' => 'Регистрация доступа к информации об инцидентах защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess2,
                'tab' => 6,
                'order' => 288
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 