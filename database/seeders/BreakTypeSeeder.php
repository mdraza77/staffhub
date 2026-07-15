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
                'name' => 'Tea / Coffee Break',
                'duration_minutes' => 15,
                'icon' => 'fa-solid fa-mug-hot',
                'is_active' => true,
            ],
            [
                'name' => 'Lunch Break',
                'duration_minutes' => 60,
                'icon' => 'fa-solid fa-utensils',
                'is_active' => true,
            ],
            [
                'name' => 'Personal Break',
                'duration_minutes' => 10,
                'icon' => 'fa-solid fa-person-walking',
                'is_active' => true,
            ],
            [
                'name' => 'Prayer Break',
                'duration_minutes' => 15,
                'icon' => 'fa-solid fa-hands-praying',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            BreakType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
