<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::role('Super Admin')->first();
        $announcements = [
            [
                'title' => 'Welcome to StaffHub',
                'message' => 'We are excited to welcome all employees to StaffHub. Please complete your profile and explore the available features.',
                'publish_date' => Carbon::now(),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'Monthly Team Meeting',
                'message' => 'A company-wide meeting has been scheduled on ' . Carbon::now()->addDays(5)->format('d F Y') . ' at 11:00 AM. All employees are requested to attend.',
                'publish_date' => Carbon::now()->subDay(),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'Salary Processed',
                'message' => 'Salary for the current month has been successfully processed and credited to employee accounts.',
                'publish_date' => Carbon::now()->subDay(2),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'Work From Home Notice',
                'message' => 'Employees may work remotely from ' . Carbon::now()->addDays(5)->format('d F Y') . ' to ' . Carbon::now()->addDays(7)->format('d F Y') . 'due to office maintenance activities.',
                'publish_date' => Carbon::now()->subDays(5),
                'priority' => 'medium',
                'status' => 'published',
            ],
            [
                'title' => 'Employee Training Program',
                'message' => 'A professional development training session will be conducted next week. Interested employees may register through HR.',
                'publish_date' => Carbon::now()->subWeek(),
                'priority' => 'medium',
                'status' => 'published',
            ],
            [
                'title' => 'System Maintenance',
                'message' => 'StaffHub will undergo scheduled maintenance on Sunday from 11:00 PM to 1:00 AM.',
                'publish_date' => Carbon::now()->subWeek(),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'New Employee Onboarding',
                'message' => 'We are pleased to welcome our new team members. Please extend your support and cooperation.',
                'publish_date' => Carbon::now(),
                'priority' => 'low',
                'status' => 'published',
            ],
            [
                'title' => 'Performance Review Cycle',
                'message' => 'The annual performance review process will begin next month. Managers are requested to complete evaluations on time.',
                'publish_date' => Carbon::now(),
                'priority' => 'medium',
                'status' => 'draft',
            ],
            [
                'title' => 'Office Security Reminder',
                'message' => 'Please ensure that your ID cards are visible while on company premises and do not share access credentials.',
                'publish_date' => Carbon::now()->subDays(3),
                'priority' => 'low',
                'status' => 'draft',
            ],
        ];

        foreach ($announcements as $announcement) {
            $announcement['created_by'] = $superAdmin?->id;
            Announcement::firstOrCreate(
                ['title' => $announcement['title']],
                $announcement
            );
        }
    }
}
