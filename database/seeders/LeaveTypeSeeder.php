<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'name' => 'Casual Leave',
                'days_allowed' => 12,
            ],
            [
                'name' => 'Sick Leave',
                'days_allowed' => 12,
            ],
            [
                'name' => 'Earned / Privilege Leave',
                'days_allowed' => 18,
            ],
            [
                'name' => 'Maternity Leave',
                'days_allowed' => 182,  // 26 weeks
            ],
            [
                'name' => 'Paternity Leave',
                'days_allowed' => 15,
            ],
            [
                'name' => 'Compensatory Off',
                'days_allowed' => 5,
            ],
            [
                'name' => 'Bereavement Leave',
                'days_allowed' => 3,
            ],
            [
                'name' => 'Marriage Leave',
                'days_allowed' => 5,
            ],
            [
                'name' => 'Loss of Pay (LOP)',
                'days_allowed' => 0,
            ],
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::firstOrCreate(
                ['name' => $leaveType['name']],
                [
                    'days_allowed' => $leaveType['days_allowed'],
                    'is_active' => true,
                ]
            );
        }
    }
}
