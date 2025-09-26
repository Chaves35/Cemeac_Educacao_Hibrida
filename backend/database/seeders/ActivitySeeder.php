<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\School;
use Faker\Factory as Faker;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        $schools = School::all();
        
        $activityTypes = [
            'verdadeiro_falso',
            'multipla_escolha',
            'drag_drop',
            'subjetiva'
        ];

        foreach ($schools as $school) {
            // Criar 8-12 atividades por escola
            $activityCount = $faker->numberBetween(8, 12);
            
            for ($i = 0; $i < $activityCount; $i++) {
                Activity::create([
                    'title' => $faker->sentence(4),
                    'description' => $faker->paragraph(),
                    'type' => $faker->randomElement($activityTypes),
                    'difficulty_level' => $faker->randomElement(['basico', 'intermediario', 'avancado']),
                    'estimated_duration' => $faker->numberBetween(30, 120),
                    'school_id' => $school->id,
                    'is_active' => $faker->boolean(85),
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
