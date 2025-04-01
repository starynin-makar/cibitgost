<?php

namespace Database\Seeders;

use App\Models\Norm;
use Illuminate\Database\Seeder;

class NormSeeder extends Seeder
{
    public function run()
    {
        $norms = [
            ['number' => 1, 'code' => 'УЗП.1', 'description' => 'Осуществление логического доступа пользователями и эксплуатационным персоналом под уникальными и персонифицированными учетными записями'],
            ['number' => 2, 'code' => 'УЗП.2', 'description' => 'Контроль соответствия фактического состава разблокированных учетных записей фактическому составу легальных субъектов логического доступа'],
            // ... добавьте остальные нормы
        ];

        foreach ($norms as $norm) {
            Norm::create($norm);
        }
    }
} 