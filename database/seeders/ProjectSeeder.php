<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'StaffHub',
                'description' => 'A comprehensive Employee Management System and HRMS platform designed to track attendance, manage leaves, allocate tasks, and generate payslips.',
                'start_date' => '2026-01-10',
                'end_date' => null,
                'status' => 'in_progress',
            ],
            [
                'name' => 'Hospital Management System',
                'description' => 'A robust system for handling clinic operations, keeping patient health records, organizing doctor scheduling, and streamlining billing workflows.',
                'start_date' => '2025-08-21',
                'end_date' => '2025-12-31',
                'status' => 'completed',
            ],
            [
                'name' => 'Aman Steel',
                'description' => 'An industrial ERP system for steel manufacturing operations to track raw material inventory, batch processing, and order shipments.',
                'start_date' => '2025-09-01',
                'end_date' => '2025-12-15',
                'status' => 'completed',
            ],
            [
                'name' => 'Zing Social Link',
                'description' => 'A high-performance community social networking application focusing on real-time messaging, post feeds, and media sharing integrations.',
                'start_date' => '2026-02-01',
                'end_date' => null,
                'status' => 'in_progress',
            ],
            [
                'name' => 'Real Estate',
                'description' => 'A property search and mortgage advisor platform featuring listing uploads, interactive map search, and loan calculator widgets.',
                'start_date' => '2025-11-01',
                'end_date' => '2026-01-15',
                'status' => 'completed',
            ],
            [
                'name' => 'Raza Store',
                'description' => 'A headless e-commerce backend system managing products, user shopping carts, coupons, payments, and automated shipping webhooks.',
                'start_date' => '2026-03-01',
                'end_date' => null,
                'status' => 'planning',
            ],
            [
                'name' => 'Task Tracker Mobile App',
                'description' => 'A companion mobile application built using React Native to sync tasks, deadlines, and project push notifications on user devices.',
                'start_date' => '2026-04-15',
                'end_date' => null,
                'status' => 'on_hold',
            ],
            [
                'name' => 'AI Business Intelligence Suite',
                'description' => 'A statistics and projection dashboard utilizing machine learning models to forecast employee productivity and company overhead costs.',
                'start_date' => '2026-06-01',
                'end_date' => null,
                'status' => 'planning',
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['name' => $project['name']],
                [
                    'description' => $project['description'],
                    'start_date' => $project['start_date'],
                    'end_date' => $project['end_date'],
                    'status' => $project['status'],
                ]
            );
        }
    }
}
