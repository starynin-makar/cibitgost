<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessElevenNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 11)->delete();

        $process = 'Процесс 11 "Реализация процесса системы защиты информации"';
        $subprocess = 'Подпроцесс "Реализация процесса системы защиты информации"';

        $norms = [
            [
                'code' => 'РЗИ.1',
                'description' => 'Реализация учета объектов и ресурсов доступа, входящих в область применения процесса системы защиты информации, для уровней информационной инфраструктуры, определенных в 6.2 настоящего стандарта, в том числе объектов доступа, расположенных в публичных (общедоступных) местах (в том числе бан��оматах, платежных терминалах)',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 349
            ],
            [
                'code' => 'РЗИ.2',
                'description' => 'Размещение и настройка (конфигурирование) технических мер защиты информации в информационной инфраструктуре финансовой организации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 350
            ],
            [
                'code' => 'РЗИ.3',
                'description' => 'Контроль (тестирование) полноты реализации технических мер защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 351
            ],
            [
                'code' => 'РЗИ.4',
                'description' => 'Назначение работникам финансовой организации ролей, связанных с применением мер защиты информации, и установление обязанности и ответственности за их выполнение',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 352
            ],
            [
                'code' => 'РЗИ.5',
                'description' => 'Определение лиц, которым разрешены действия по внесению изменений в конфигурацию информационной инфраструктуры',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 353
            ],
            [
                'code' => 'РЗИ.6',
                'description' => 'Реализация эксплуатации, использования по назначению технических мер защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 354
            ],
            [
                'code' => 'РЗИ.7',
                'description' => 'Реализация применения организационных мер защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 355
            ],
            [
                'code' => 'РЗИ.8',
                'description' => 'Реализация централизованного управления техническими мерами защиты информации*',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 356
            ],
            [
                'code' => 'РЗИ.9',
                'description' => 'Обеспечение доступности технических мер защиты информации: - применение отказоустойчивых технических решений; - резервирование информационной инфраструктуры, необходимой для функционирования технических мер защиты информации; - осуществление контроля безотказного функционирования технических мер защиты информации; - принятие регламентированных мер по восстановлению отказавших технических мер защиты информации информационной инфраструктуры, необходимых для их функционирования',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 357
            ],
            [
                'code' => 'РЗИ.10',
                'description' => 'Обеспечение возможности сопровождения технических мер защиты информации в течение всего срока их использования',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 358
            ],
            [
                'code' => 'РЗИ.11',
                'description' => 'Применение сертифицированных по требованиям безопасности информации средств защиты информации не ниже 4 класса**',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 359
            ],
            [
                'code' => 'РЗИ.12',
                'description' => 'Применение сертифицированных по требованиям безопасности информации средств защиты информации не ниже 5 класса**',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 360
            ],
            [
                'code' => 'РЗИ.13',
                'description' => 'Применение сертифицированных по требованиям безопасности информации средств защиты информации не ниже 6 класса**',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 361
            ],
            [
                'code' => 'РЗИ.14',
                'description' => 'Применение СКЗИ, имеющих класс не ниже КС2**',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 362
            ],
            [
                'code' => 'РЗИ.15',
                'description' => 'Обучение, практическая подготовка (переподготовка) работников финансовой организации, ответственных за применение мер защиты информации в рамках процесса защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 363
            ],
            [
                'code' => 'РЗИ.16',
                'description' => 'Повышение осведомленности (инструктаж) работников финансовой организации в области реализации процесса защиты информации, применения организационных мер защиты информации, использования по назначению технических мер защиты информации',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess,
                'tab' => 11,
                'order' => 364
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 