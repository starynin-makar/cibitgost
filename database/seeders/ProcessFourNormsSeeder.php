<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessFourNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 4)->delete();

        $process = 'Процесс 4 "Защита от вредоносного кода"';
        $subprocess1 = 'Подпроцесс "Защита от вредоносного кода"';

        $norms = [
            [
                'code' => 'ЗВК.1',
                'description' => 'Реализация защиты от вредоносного кода на уровне физических АРМ пользователей и эксплуатационного персонала',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 186
            ],
            [
                'code' => 'ЗВК.2',
                'description' => 'Реализация защиты от вредоносного кода на уровне виртуальной информационной инфраструктуры',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 187
            ],
            [
                'code' => 'ЗВК.3',
                'description' => 'Реализация защиты от вредоносного кода на уровне серверного оборудования',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 188
            ],
            [
                'code' => 'ЗВК.4',
                'description' => 'Реализация защиты от вредоносного кода на уровне контроля межсетевого трафика',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 189
            ],
            [
                'code' => 'ЗВК.5',
                'description' => 'Реализация защиты от вредоносного кода на уровне контроля почтового трафика',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 190
            ],
            [
                'code' => 'ЗВК.6',
                'description' => 'Реализация защиты от вредоносного кода на уровне входного контроля устройств и переносных (отчуждаемых) носителей информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 191
            ],
            [
                'code' => 'ЗВК.7',
                'description' => 'Реализация защиты от вредоносного кода на уровне контроля общедоступных объектов доступа (в том числе банкоматов, платежных терминалов)',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 192
            ],
            [
                'code' => 'ЗВК.8',
                'description' => 'Функционирование средств защиты от вредоносного кода в постоянном, автоматическом режиме, в том числе в части установки их обновлений и сигнатурных баз данных',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 193
            ],
            [
                'code' => 'ЗВК.9',
                'description' => 'Функционирование средств защиты от вредоносного кода на АРМ пользователей и эксплуатационного персонала в резидентном режиме (в режиме service - для операционной системы Windows, в режиме daemon - для операционной системы Unix), их автоматический запуск при загрузке операционной системы',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 194
            ],
            [
                'code' => 'ЗВК.10',
                'description' => 'Применение средств защиты от вредоносного кода, реализующих функцию контроля целостности их программных компонентов',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 195
            ],
            [
                'code' => 'ЗВК.11',
                'description' => 'Контроль отключения и своевременного обновления средств защиты от вредоносного кода',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 196
            ],
            [
                'code' => 'ЗВК.12',
                'description' => 'Выполнение еженедельных операций по проведению проверок на отсутствие вредоносного кода',
                'implementation_type' => '��',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 197
            ],
            [
                'code' => 'ЗВК.13',
                'description' => 'Использование средств защиты от вредоносного кода различных производителей, как минимум для уровней:\n\n- физические АРМ пользователей и эксплуатационного персонала;\n\n- серверное оборудование',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 198
            ],
            [
                'code' => 'ЗВК.14',
                'description' => 'Использование средств защиты от вредоносного кода различных производителей, как минимум для уровней:\n\n- физические АРМ пользователей и эксплуатационного персонала;\n\n- серверное оборудование;\n\n- контроль межсетевого трафика',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 199
            ],
            [
                'code' => 'ЗВК.15',
                'description' => 'Выполнение проверок на отсутствие вредоносного кода путем анализа информационных потоков между сегментами контуров безопасности и иными внутренними вычислительными сетями финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 200
            ],
            [
                'code' => 'ЗВК.16',
                'description' => 'Выполнение проверок на отсутствие вредоносного кода путем анализа информационных потоков между внутренними вычислительными сетями финансовой организации и сетью Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 201
            ],
            [
                'code' => 'ЗВК.17',
                'description' => 'Выполнение проверок на отсутствие вредоносного кода путем анализа информационных потоков между сегментами, предназначенными для размещения общедоступных объектов доступа (в том числе банкоматов, платежных терминалов), и сетью Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 202
            ],
            [
                'code' => 'ЗВК.18',
                'description' => 'Входной контроль всех устройств и переносных (отчуждаемых) носителей информации (включая мобильные компьютеры и флеш-накопители) перед их использованием в вычислительных сетях финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 203
            ],
            [
                'code' => 'ЗВК.19',
                'description' => 'Входной контроль устройств и переносных (отчуждаемых) носителей информации перед их использованием в вычислительных сетях финансовой организации, в выделенном сегменте вычислительной сети, с исключением возможности информационного взаимодействия указанного сегмента и иных сегментов вычислительных сетей финансовой организации (кроме управляющего информационного взаимодействия по установленным правилам и протоколам)',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 204
            ],
            [
                'code' => 'ЗВК.20',
                'description' => 'Выполнение предварительных проверок на отсутствие вредоносного кода устанавливаемого или изменяемого ПО, а также выполнение проверки после установки и (или) изменения ПО',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 205
            ],
            [
                'code' => 'ЗВК.21',
                'description' => 'Запрет неконтролируемого открытия самораспаковывающихся архивов и исполняемых файлов, полученных из сети Интернет',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 206
            ],
            [
                'code' => 'ЗВК.22',
                'description' => 'Регистрация операций по проведению проверок на отсутствие вредоносного кода',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 207
            ],
            [
                'code' => 'ЗВК.23',
                'description' => 'Регистрация фактов выявления вредоносного кода',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 208
            ],
            [
                'code' => 'ЗВК.24',
                'description' => 'Регистрация неконтролируемого использования технологии мобильного кода*',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 209
            ],
            [
                'code' => 'ЗВК.25',
                'description' => 'Регистрация сбоев в функционировании средств защиты от вредоносного кода',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 210
            ],
            [
                'code' => 'ЗВК.26',
                'description' => 'Регистрация сбоев в выполнении контроля (проверок) на отсутствие вредоносного кода',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 211
            ],
            [
                'code' => 'ЗВК.27',
                'description' => 'Регистрация отключения средств защиты от вредоносного кода',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 212
            ],
            [
                'code' => 'ЗВК.28',
                'description' => 'Регистрация нарушений целостности программных компонентов средств защиты от вредоно��ного кода',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 4,
                'order' => 213
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 