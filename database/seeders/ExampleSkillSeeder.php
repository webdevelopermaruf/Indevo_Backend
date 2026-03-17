<?php

namespace Database\Seeders;

use App\Enums\SkillCategories;
use App\Models\Skill;
use App\Models\SkillStep;
use Illuminate\Database\Seeder;

class ExampleSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $difficulty = ['easy', 'medium', 'hard'];
        $category = SkillCategories::cases();
        // creates multiple skills
        for($i=1;$i<3;$i++){
            Skill::insert([
                'title' => 'Skill '.$i,
                'achievement' => 'skill '.$i,
                'poster' => 'skill '.$i,
                'duration' => rand(10, 1000),
                'reward' => rand(1, 19) * 40,
                'difficulty' => $difficulty[array_rand($difficulty)],
                'category' => $category[array_rand($category)]->value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Skill Steps
        for($i=1;$i<3;$i++){
            for($j=0;$j<$i * 5;$j++){
                SkillStep::insert([
                    'skill_id' => $i,
                    'position' => $j,
                    'title' => "Step". $j . " of $i ",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
