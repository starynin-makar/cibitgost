<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $organizations = [
            [
                'name' => 'АО "НС Банк"',
                'director' => 'Картаев Сергей Николаевич',
                'status' => 1,
                'user_id' => 1,
                'address' => 'г. Москва, Гагаринский пер., д. 17, стр. 1',
                'phone' => '+7 (495) 276-11-11',
                'email' => 'info@nsbank.ru'
            ],
            [
                'name' => 'ПАО «ЧЕЛИНДБАНК»',
                'director' => 'Братишкин Михаил Иванович',
                'status' => 1,
                'user_id' => 1,
                'address' => 'г. Челябинск, ул. Карла Маркса, д. 80',
                'phone' => '+7 (351) 239-88-99',
                'email' => 'info@chelindbank.ru'
            ],
            [
                'name' => 'АО «Россельхозбанк»',
                'director' => 'Листов Б.П.',
                'status' => 1,
                'user_id' => 1,
                'address' => 'г. Москва, Гагаринский пер., д. 3',
                'phone' => '+7 (495) 777-11-00',
                'email' => 'info@rshb.ru'
            ],
            [
                'name' => 'Капитал Лайф',
                'director' => 'Капитал Лайф',
                'status' => 1,
                'user_id' => 1,
                'address' => 'г. Москва, ул. Киевская, д. 7',
                'phone' => '+7 (495) 777-77-77',
                'email' => 'info@kaplife.ru'
            ],
            [
                'name' => 'Райффайзен',
                'director' => 'Райффайзен',
                'status' => 1,
                'user_id' => 1,
                'address' => 'г. Москва, ул. Троицкая, д. 17, стр. 1',
                'phone' => '+7 (495) 721-99-00',
                'email' => 'info@raiffeisen.ru'
            ]
        ];

        foreach ($organizations as $org) {
            Organization::create($org);
        }
    }
} 