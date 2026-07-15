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
                'message' => 'Welcome to StaffHub. Please complete your profile information, upload required documents, and review company policies available on the portal.',
                'publish_date' => Carbon::now(),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'Independence Day Holiday',
                'message' => 'The office will remain closed on 15 August in observance of Independence Day. Regular operations will resume on the next working day.',
                'publish_date' => Carbon::now()->subDays(2),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'Payroll Processed Successfully',
                'message' => 'Monthly payroll has been processed successfully. Employees are requested to review their payslips through the Payroll section.',
                'publish_date' => Carbon::now()->subDays(5),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'Quarterly Performance Review',
                'message' => 'Managers are requested to complete employee performance evaluations before the review cycle deadline.',
                'publish_date' => Carbon::now()->subWeek(),
                'priority' => 'medium',
                'status' => 'published',
            ],
            [
                'title' => 'Mandatory Security Awareness Training',
                'message' => 'All employees are required to complete the annual cybersecurity and data protection training program before the due date.',
                'publish_date' => Carbon::now()->subDays(10),
                'priority' => 'medium',
                'status' => 'published',
            ],
            [
                'title' => 'Scheduled System Maintenance',
                'message' => 'StaffHub will undergo scheduled maintenance this weekend. Some services may be temporarily unavailable during the maintenance window.',
                'publish_date' => Carbon::now()->subDays(12),
                'priority' => 'high',
                'status' => 'published',
            ],
            [
                'title' => 'Employee Referral Program',
                'message' => 'Employees are encouraged to refer qualified candidates for open positions. Successful referrals may be eligible for referral rewards.',
                'publish_date' => Carbon::now()->subDays(15),
                'priority' => 'low',
                'status' => 'published',
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
