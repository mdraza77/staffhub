<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

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
                'days_allowed' => 10,
            ],
            [
                'name' => 'Earned Leave',
                'days_allowed' => 18,
            ],
            [
                'name' => 'Maternity Leave',
                'days_allowed' => 180,
            ],
            [
                'name' => 'Paternity Leave',
                'days_allowed' => 15,
            ],
            [
                'name' => 'Marriage Leave',
                'days_allowed' => 7,
            ],
            [
                'name' => 'Bereavement Leave',
                'days_allowed' => 5,
            ],
            [
                'name' => 'Unpaid Leave',
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
