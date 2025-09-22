// backend/database/seeders/SchoolSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar dados existentes na tabela schools
        School::query()->delete();

        // Definir escolas de exemplo para seed
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
            ]
        ];

        // Inserir escolas no banco de dados
        School::insert($schools);
    }
}