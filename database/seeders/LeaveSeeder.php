<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $leaveTypes = LeaveType::all();

        if ($users->isEmpty() || $leaveTypes->isEmpty()) {
            return;
        }

        $reasons = [
            'Family medical emergency',
            'Personal work at home',
            'Going to hometown for a festival',
            'Not feeling well, severe headache',
            'Doctor appointment and routine checkup',
            "Attending friend's wedding ceremony",
            'Urgent repair work at house',
            'Resting due to fever and cold',
            "Renewing driver's license and passport",
            'Travel plans with family'
        ];

        foreach ($users as $user) {
            if (Leave::where('user_id', $user->id)->exists()) {
                continue;
            }

            // 1. Create a past leave (approved or rejected)
            $pastLeaveType = $leaveTypes->random();
            $pastDays = rand(1, 3);
            $pastStart = now()->subDays(rand(10, 30));
            $pastEnd = (clone $pastStart)->addDays($pastDays - 1);
            $pastStatus = rand(0, 10) > 3 ? 'approved' : 'rejected';
            $pastRemark = $pastStatus === 'approved' ? 'Approved by HR Manager.' : 'Rejected due to critical project deadline.';

            Leave::create([
                'user_id' => $user->id,
                'leave_type_id' => $pastLeaveType->id,
                'start_date' => $pastStart->format('Y-m-d'),
                'end_date' => $pastEnd->format('Y-m-d'),
                'total_days' => $pastDays,
                'reason' => $reasons[array_rand($reasons)],
                'status' => $pastStatus,
                'admin_remark' => $pastRemark,
            ]);

            // 2. Create a future pending leave
            $futureLeaveType = $leaveTypes->random();
            $futureDays = rand(1, 4);
            $futureStart = now()->addDays(rand(2, 15));
            $futureEnd = (clone $futureStart)->addDays($futureDays - 1);

            Leave::create([
                'user_id' => $user->id,
                'leave_type_id' => $futureLeaveType->id,
                'start_date' => $futureStart->format('Y-m-d'),
                'end_date' => $futureEnd->format('Y-m-d'),
                'total_days' => $futureDays,
                'reason' => $reasons[array_rand($reasons)],
                'status' => 'pending',
                'admin_remark' => null,
            ]);
        }
    }
}
