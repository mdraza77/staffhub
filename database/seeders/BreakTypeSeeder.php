<?php

namespace Database\Seeders;

use App\Models\BreakType;
use Illuminate\Database\Seeder;

class BreakTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Tea Break',
                'duration_minutes' => 15,
                'icon' => 'fa-solid fa-mug-hot',
                'is_active' => true,
            ],
            [
                'name' => 'Lunch Break',
                'duration_minutes' => 45,
                'icon' => 'fa-solid fa-utensils',
                'is_active' => true,
            ],
            [
                'name' => 'Quick Refreshment',
                'duration_minutes' => 10,
                'icon' => 'fa-solid fa-apple-whole',
                'is_active' => true,
            ],
            [
                'name' => 'Smoke Break',
                'duration_minutes' => 10,
                'icon' => 'fa-solid fa-smoking',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            BreakType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
