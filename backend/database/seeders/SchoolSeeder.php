<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar dados existentes
        School::query()->delete();

        // Escolas de exemplo
        $schools = [
            [
                'name' => 'CEMEAC - Unidade Central',
                'inep_code' => '12345678',
                'address' => 'Rua Principal, 100',
                'city' => 'São Paulo',
                'state' => 'SP'
            ],
            [
                'name' => 'CEMEAC - Unidade Norte',
                'inep_code' => '87654321',
                'address' => 'Avenida Secundária, 250',
                'city' => 'Campinas',
                'state' => 'SP'
            ],
            [
                'name' => 'CEMEAC - Unidade Sul',
                'inep_code' => '45678912',
                'address' => 'Praça da Educação, 350',
                'city' => 'Santos',
                'state' => 'SP'
            ]
        ];

        // Criar escolas usando create
        foreach ($schools as $schoolData) {
            School::create($schoolData);
        }
    }
}