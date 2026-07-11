<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'Super Admin');
        })->get();

        if ($users->isEmpty()) {
            return;
        }

        $startDate = now()->startOfMonth();
        $endDate = now(); // Seed up to today

        // 1. Generate list of working days (excluding Sundays) from start of month to today
        $workingDates = [];
        $temp = clone $startDate;
        while ($temp->lte($endDate)) {
            if (!$temp->isSunday()) {
                $workingDates[] = $temp->format('Y-m-d');
            }
            $temp->addDay();
        }

        if (empty($workingDates)) {
            return;
        }

        // We will target specific users to make them absent on fixed working days
        // Employee 1: index 0 (usually employee@gmail.com) -> Absent on the first 2 working days
        // Employee 2: index 1 -> Absent on the next 3 working days

        foreach ($users as $index => $user) {
            foreach ($workingDates as $dateIndex => $date) {
                $isAbsent = false;

                // Intentionally make specific employees absent on fixed dates
                if ($index === 0 && ($dateIndex === 0 || $dateIndex === 1)) {
                    $isAbsent = true;
                } elseif ($index === 1 && ($dateIndex === 2 || $dateIndex === 3 || $dateIndex === 4)) {
                    $isAbsent = true;
                }

                if ($isAbsent) {
                    Attendance::firstOrCreate(
                        ['user_id' => $user->id, 'date' => $date],
                        [
                            'check_in_time' => null,
                            'check_out_time' => null,
                            'status' => 'absent',
                            'note' => 'Intentionally absent (Fixed Seeder Day)',
                        ]
                    );
                } else {
                    // Normal present day
                    // Generate random check-in around 9:00 AM (8:45 AM - 9:15 AM)
                    $checkInHour = 8;
                    $checkInMin = rand(45, 59);
                    if (rand(0, 1)) {
                        $checkInHour = 9;
                        $checkInMin = rand(0, 15);
                    }
                    $checkInTime = sprintf('%02d:%02d:00', $checkInHour, $checkInMin);

                    // Generate random check-out around 6:00 PM (5:45 PM - 6:15 PM)
                    $checkOutHour = 17;
                    $checkOutMin = rand(45, 59);
                    if (rand(0, 1)) {
                        $checkOutHour = 18;
                        $checkOutMin = rand(0, 15);
                    }
                    $checkOutTime = sprintf('%02d:%02d:00', $checkOutHour, $checkOutMin);

                    Attendance::firstOrCreate(
                        ['user_id' => $user->id, 'date' => $date],
                        [
                            'check_in_time' => $checkInTime,
                            'check_out_time' => $checkOutTime,
                            'status' => 'present',
                            'note' => 'Completed regular shift.',
                        ]
                    );
                }
            }
        }

        $this->command->info('Attendance seeded successfully for the current month.');
    }
}
