<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessTwelveNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 12)->delete();

        $process = 'Процесс 12 "Контроль процесса системы защиты информации"';
        $subprocess = 'Подпроцесс "Контроль процесса системы защиты информации"';

        $norms = [
            [
                'code' => 'КЗИ.1',
                'description' => 'Контроль фактического состава объектов и ресурсов доступа, входящих в область применения процесса системы защиты информации, на соответствие учетным данным, формируемым в рамках выполнения меры РЗИ.1 таблицы 49',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 365
            ],
            [
                'code' => 'КЗИ.2',
                'description' => 'Контроль эксплуатации и использования по назначению технических мер защиты информации, включающий: - контроль фактического размещения технических мер защиты информации в информационной инфраструктуре финансовой организации; - контроль фактических параметров настроек технических мер защиты информации и компонентов информационной инфраструктуры, предназначенных для размещения технических мер защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 366
            ],
            [
                'code' => 'КЗИ.3',
                'description' => 'Контроль эксплуатации и использования по назначению технических мер защиты информации, включающий: - контроль назначения ролей, связанных с эксплуатацией и использованием по назначению технических мер защиты информации; - контроль выполнения руководств по эксплуатации и использованию по назначению технических мер защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 367
            ],
            [
                'code' => 'КЗИ.4',
                'description' => 'Периодический контроль (тестирование) полноты реализации технических мер защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 368
            ],
            [
                'code' => 'КЗИ.5',
                'description' => 'Контроль применения организационных мер защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 369
            ],
            [
                'code' => 'КЗИ.6',
                'description' => 'Контроль безотказного функционирования технических средств, обнаружение и локализация отказов функционирования, принятие мер по восстановлению отказавших средств и их тестирование',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 370
            ],
            [
                'code' => 'КЗИ.7',
                'description' => 'Проведение проверок знаний работников финансовой организации в части применения мер защиты информации в рамках процесса системы защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 371
            ],
            [
                'code' => 'КЗИ.8',
                'description' => 'Фиксация результатов (свидетельств) проведения мероприятий по контролю реализации процесса системы защиты информации, проводимых в соответствии с мерами КЗИ.1-КЗИ.7 настоящей таблицы',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 372
            ],
            [
                'code' => 'КЗИ.9',
                'description' => 'Регистрация операций по установке и (или) обновлению ПО технических средств защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 373
            ],
            [
                'code' => 'КЗИ.10',
                'description' => 'Регистрация операций по обн��влению сигнатурных баз технических средств защиты информации (в случае их использования)',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 374
            ],
            [
                'code' => 'КЗИ.11',
                'description' => 'Регистрация операций по изменению параметров настроек технических мер защиты информации и информационной инфраструктуры, предназначенных для размещения технических мер защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 375
            ],
            [
                'code' => 'КЗИ.12',
                'description' => 'Регистрация сбоев (отказов) технических мер защиты информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 12,
                'order' => 376
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 