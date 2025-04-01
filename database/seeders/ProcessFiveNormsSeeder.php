<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProcessFiveNormsSeeder extends Seeder
{
    public function run()
    {
        DB::table('norms')->where('tab', 5)->delete();

        $process = 'Процесс 5 "Предотвращение утечек информации"';
        $subprocess1 = 'Подпроцесс "Предотвращение утечек информации"';

        $norms = [
            [
                'code' => 'ПУИ.1',
                'description' => 'Блокирование неразрешенной и контроль (анализ) разрешенной передачи информации конфиденциального характера на внешние адреса электронной почты',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 214
            ],
            [
                'code' => 'ПУИ.2',
                'description' => 'Бл��кирование неразрешенной и контроль (анализ) разрешенной передачи информации конфиденциального характера в сеть Интернет с использованием информационной инфраструктуры финансовой организации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 215
            ],
            [
                'code' => 'ПУИ.3',
                'description' => 'Блокирование неразрешенной и контроль (анализ) разрешенной печати информации конфиденциального характера',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 216
            ],
            [
                'code' => 'ПУИ.4',
                'description' => 'Блокирование неразрешенного и контроль (анализ) разрешенного копирования информации конфиденциального ��арактера на переносные (отчуждаемые) носители информации',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 217
            ],
            [
                'code' => 'ПУИ.5',
                'description' => 'Контентный анализ передаваемой информации по протоколам исходящего почтового обмена',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 218
            ],
            [
                'code' => 'ПУИ.6',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее 6 мес и оперативным доступом на срок не менее 1 мес',
                'implementation_type' => 'Т',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 219
            ],
            [
                'code' => 'ПУИ.7',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 220
            ],
            [
                'code' => 'ПУИ.8',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 221
            ],
            [
                'code' => 'ПУИ.9',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 222
            ],
            [
                'code' => 'ПУИ.10',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 223
            ],
            [
                'code' => 'ПУИ.11',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 224
            ],
            [
                'code' => 'ПУИ.12',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 225
            ],
            [
                'code' => 'ПУИ.13',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 226
            ],
            [
                'code' => 'ПУИ.14',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 227
            ],
            [
                'code' => 'ПУИ.15',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 228
            ],
            [
                'code' => 'ПУИ.16',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 229
            ],
            [
                'code' => 'ПУИ.17',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 230
            ],
            [
                'code' => 'ПУИ.18',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 231
            ],
            [
                'code' => 'ПУИ.19',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 232
            ],
            [
                'code' => 'ПУИ.20',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 233
            ],
            [
                'code' => 'ПУИ.21',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 234
            ],
            [
                'code' => 'ПУИ.22',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 235
            ],
            [
                'code' => 'ПУИ.23',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 236
            ],
            [
                'code' => 'ПУИ.24',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 237
            ],
            [
                'code' => 'ПУИ.25',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 238
            ],
            [
                'code' => 'ПУИ.26',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 239
            ],
            [
                'code' => 'ПУИ.27',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 240
            ],
            [
                'code' => 'ПУИ.28',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 241
            ],
            [
                'code' => 'ПУИ.29',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 242
            ],
            [
                'code' => 'ПУИ.30',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 243
            ],
            [
                'code' => 'ПУИ.31',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 244
            ],
            [
                'code' => 'ПУИ.32',
                'description' => 'Ведение единого архива электронных сообщений с архивным доступом на срок не менее одного года и оперативным доступом на срок не менее 3 мес',
                'implementation_type' => 'Н',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 245
            ],
            [
                'code' => 'ПУИ.33',
                'description' => 'Регистрация фактов стирания информации с МНИ',
                'implementation_type' => 'О',
                'process_name' => $process,
                'subprocess_name' => $subprocess1,
                'tab' => 5,
                'order' => 246
            ]
        ];

        foreach ($norms as $norm) {
            DB::table('norms')->insert($norm);
        }
    }
} 