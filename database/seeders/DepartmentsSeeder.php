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
                'description' => 'Manages recruitment, onboarding, employee relations, and company policies.'
            ],
            [
                'name' => 'Engineering',
                'slug' => 'engineering',
                'description' => 'Develops and maintains software products and technical solutions.'
            ],
            [
                'name' => 'Quality Assurance',
                'slug' => 'quality-assurance',
                'description' => 'Ensures product quality through testing and validation.'
            ],
            [
                'name' => 'Product Management',
                'slug' => 'product-management',
                'description' => 'Defines product strategy, roadmap, and business requirements.'
            ],
            [
                'name' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'description' => 'Designs user interfaces and improves user experience.'
            ],
            [
                'name' => 'DevOps & Infrastructure',
                'slug' => 'devops-infrastructure',
                'description' => 'Manages deployments, cloud infrastructure, and system reliability.'
            ],
            [
                'name' => 'Finance & Accounts',
                'slug' => 'finance-accounts',
                'description' => 'Handles accounting, payroll, budgeting, and financial reporting.'
            ],
            [
                'name' => 'Sales',
                'slug' => 'sales',
                'description' => 'Drives revenue growth and customer acquisition.'
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Promotes products, branding, and digital marketing campaigns.'
            ],
            [
                'name' => 'Customer Support',
                'slug' => 'customer-support',
                'description' => 'Provides customer assistance and resolves service issues.'
            ],
            [
                'name' => 'Operations',
                'slug' => 'operations',
                'description' => 'Oversees day-to-day business operations and process management.'
            ],
            [
                'name' => 'Administration',
                'slug' => 'administration',
                'description' => 'Handles office management and internal coordination.'
            ],
            [
                'name' => 'Legal & Compliance',
                'slug' => 'legal-compliance',
                'description' => 'Ensures regulatory compliance and manages legal matters.'
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
