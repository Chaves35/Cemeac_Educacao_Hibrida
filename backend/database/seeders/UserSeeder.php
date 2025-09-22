<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar dados existentes
        User::query()->delete();

        // Buscar escolas
        $schools = School::all();

        // Usuários de exemplo
        $users = [
            [
                'name' => 'Administrador Geral',
                'email' => 'admin@cemeac.edu.br',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'school_id' => null
            ],
            [
                'name' => 'Gestor Unidade Central',
                'email' => 'gestor.central@cemeac.edu.br',
                'password' => Hash::make('password123'),
                'role' => 'gestor',
                'school_id' => $schools->first()->id
            ],
            [
                'name' => 'Professor Unidade Norte',
                'email' => 'professor.norte@cemeac.edu.br',
                'password' => Hash::make('password123'),
                'role' => 'professor',
                'school_id' => $schools->where('name', 'CEMEAC - Unidade Norte')->first()->id
            ],
            [
                'name' => 'Aluno Unidade Sul',
                'email' => 'aluno.sul@cemeac.edu.br',
                'password' => Hash::make('password123'),
                'role' => 'aluno',
                'school_id' => $schools->last()->id
            ]
        ];

        // Inserir usuários
        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}