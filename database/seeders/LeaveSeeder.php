<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->get();

        if ($users->isEmpty()) {
            return;
        }

        $leaveData = [
            'Casual Leave' => [
                'Personal work at home',
                'Family function',
                'Attending a wedding ceremony',
                'Travel plans with family',
            ],
            'Sick Leave' => [
                'Fever and cold',
                'Doctor appointment',
                'Migraine and severe headache',
                'Food poisoning and rest advised',
            ],
            'Earned / Privilege Leave' => [
                'Family vacation',
                'Outstation travel plans',
                'Personal commitments',
                'Long weekend leave',
            ],
            'Maternity Leave' => [
                'Maternity leave application',
            ],
            'Paternity Leave' => [
                'Paternity leave for newborn child',
            ],
            'Compensatory Off' => [
                'Worked on weekend deployment',
                'Worked on public holiday',
            ],
            'Bereavement Leave' => [
                'Family bereavement',
            ],
            'Marriage Leave' => [
                'Marriage ceremony and related events',
            ],
            'Loss of Pay (LOP)' => [
                'Additional personal leave requirement',
            ],
        ];

        $statuses = [
            'approved',
            'approved',
            'approved',
            'approved',
            'approved',
            'approved',
            'approved',
            'pending',
            'pending',
            'rejected',
        ];

        foreach ($users as $index => $user) {
            if (Leave::where('user_id', $user->id)->exists()) {
                continue;
            }

            $leaveType = LeaveType::inRandomOrder()->first();

            $reasons = $leaveData[$leaveType->name] ?? [
                'Personal leave request'
            ];

            $reason = $reasons[array_rand($reasons)];

            $startDate = Carbon::now()->addDays(rand(2, 20));

            $endDate = (clone $startDate)->addDays(rand(0, 3));

            $totalDays = $startDate->diffInDays($endDate) + 1;

            $status = $statuses[$index % count($statuses)];

            Leave::create([
                'user_id' => $user->id,
                'leave_type_id' => $leaveType->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'total_days' => $totalDays,
                'reason' => $reason,
                'status' => $status,
                'admin_remark' => match ($status) {
                    'approved' => 'Approved by reporting manager.',
                    'rejected' => 'Leave request rejected due to business requirements.',
                    default => null,
                },
            ]);
        }
    }
}
