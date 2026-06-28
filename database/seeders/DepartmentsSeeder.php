<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'slug' => 'human-resources',
                'description' => 'Manages recruitment, employee relations, and company policies.'
            ],
            [
                'name' => 'Information Technology',
                'slug' => 'information-technology',
                'description' => 'Handles software, hardware, and technical infrastructure.'
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'description' => 'Responsible for budgeting, accounting, and financial planning.'
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Promotes products and manages brand awareness.'
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'Handles customer acquisition and revenue generation.'
            ],
            [
                'name' => 'Operations',
                'slug' => 'operations',
                'description' => 'Oversees daily business operations and processes.'
            ],
            [
                'name' => 'Customer Support',
                'slug' => 'customer-support',
                'description' => 'Assists customers and resolves support issues.'
            ],
            [
                'name' => 'Research & Development',
                'slug' => 'research-development',
                'description' => 'Focuses on innovation and new product development.'
            ],
            [
                'name' => 'Administration',
                'slug' => 'administration',
                'description' => 'Manages office administration and internal coordination.'
            ],
            [
                'name' => 'Legal & Compliance',
                'slug' => 'legal-compliance',
                'description' => 'Ensures legal compliance and handles corporate legal matters.'
            ],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(
                ['slug' => $department['slug']],
                $department
            );
        }
    }
}
