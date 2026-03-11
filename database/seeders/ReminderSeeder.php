<?php

namespace Database\Seeders;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $tasks = [];

        for ($day = 0; $day < 14; $day++) {
            $date = now()->addDays($day)->toDateString();

            $tasks = array_merge($tasks, [
                // Morning tasks
                [
                    'user_id'     => $user->id,
                    'description' => 'Morning Vitamins',
                    'priority'    => 'low',
                    'category'    => 'health',
                    'due_time'    => '08:00:00',
                    'due_date'    => $date,
                    'recurrence'  => 'daily',
                    'place'       => null,
                    'note'        => 'Take vitamin C and D',
                    'is_completed' => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'user_id'     => $user->id,
                    'description' => 'Morning Run',
                    'priority'    => 'medium',
                    'category'    => 'health',
                    'due_time'    => '07:00:00',
                    'due_date'    => $date,
                    'recurrence'  => 'daily',
                    'place'       => 'Park',
                    'note'        => '30 minute jog',
                    'is_completed' => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Afternoon tasks
                [
                    'user_id'     => $user->id,
                    'description' => 'Pay Electricity Bill',
                    'priority'    => 'high',
                    'category'    => 'personal',
                    'due_time'    => '14:00:00',
                    'due_date'    => $date,
                    'recurrence'  => 'once',
                    'place'       => null,
                    'note'        => 'Urgent - due today',
                    'is_completed' => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'user_id'     => $user->id,
                    'description' => 'Do Laundry',
                    'priority'    => 'low',
                    'category'    => 'personal',
                    'due_time'    => '15:00:00',
                    'due_date'    => $date,
                    'recurrence'  => 'weekly',
                    'place'       => 'Home',
                    'note'        => null,
                    'is_completed' => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],

                // Evening tasks
                [
                    'user_id'     => $user->id,
                    'description' => 'Read a Book',
                    'priority'    => 'medium',
                    'category'    => 'learning',
                    'due_time'    => '19:00:00',
                    'due_date'    => $date,
                    'recurrence'  => 'daily',
                    'place'       => null,
                    'note'        => '30 minutes before bed',
                    'is_completed' => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
                [
                    'user_id'     => $user->id,
                    'description' => 'Team Standup Meeting',
                    'priority'    => 'high',
                    'category'    => 'work',
                    'due_time'    => '17:00:00',
                    'due_date'    => $date,
                    'recurrence'  => 'daily',
                    'place'       => 'Office',
                    'note'        => 'Daily sync with team',
                    'is_completed' => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ],
            ]);
        }

        Reminder::insert($tasks);
    }
}
