<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Отключаем проверку внешних ключей
        Schema::disableForeignKeyConstraints();

        // Очищаем таблицы перед заполнением
        DB::table('users')->truncate();
        DB::table('norms')->truncate();
        
        // Включаем проверку внешних ключей обратно
        Schema::enableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            // Сидеры для вкладок в правильном порядке
            ProcessOneNormsSeeder::class,    // Вкладка 1
            ProcessTwoNormsSeeder::class,    // Вкладка 2
            ProcessThreeNormsSeeder::class,  // Вкладка 3
            ProcessFourNormsSeeder::class,   // Вкладка 4
            ProcessFiveNormsSeeder::class,   // Вкладка 5
            ProcessSixNormsSeeder::class,    // Вкладка 6
            ProcessSevenNormsSeeder::class,  // Вкладка 7
            ProcessEightNormsSeeder::class,  // Вкладка 8
            ProcessNineNormsSeeder::class,   // Вкладка 9
            ProcessTenNormsSeeder::class,    // Вкладка 10
            ProcessElevenNormsSeeder::class, // Вкладка 11
            ProcessTwelveNormsSeeder::class, // Вкладка 12
            ProcessThirteenNormsSeeder::class, // Вкладка 13
            ProcessFourteenNormsSeeder::class, // Вкладка 14
            OrganizationSeeder::class,
        ]);
    }
}
