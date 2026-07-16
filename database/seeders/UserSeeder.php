<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Fetch departments for referencing
        $hrDept = Department::where('slug', 'human-resources')->first();
        $itDept = Department::where('slug', 'engineering')->first();
        $opsDept = Department::where('slug', 'operations')->first();
        $adminDept = Department::where('slug', 'administration')->first();
        $rndDept = Department::where('slug', 'engineering')->first();

        // Auto-generate employee ID
        $todayYear = Carbon::now()->format('y');  // e.g., 26
        $todayMonth = Carbon::now()->format('m');  // e.g., 07
        $prefix = "SH-{$todayYear}{$todayMonth}-";

        $existingEmployeeIds = User::where('employee_id', 'LIKE', $prefix . '%')
            ->pluck('employee_id')
            ->map(function ($id) {
                return (int) substr($id, -3);
            })
            ->toArray();

        $nextSeq = !empty($existingEmployeeIds) ? max($existingEmployeeIds) + 1 : 1;

        // Placeholder for user array structure compatibility
        $nextEmployeeId = '';

        // 3. Define dummy users for each role
        $users = [
            [
                'name' => 'Md Raza',
                'email' => 'admin@gmail.com',
                'role' => 'Super Admin',
                'employee_id' => $nextEmployeeId,
                'phone' => '+917477650108',
                'department_id' => $adminDept?->id,
                'designation' => 'Chief Executive Officer',
                'joining_date' => '2026-01-01',
                'status' => 'active'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'admin2@gmail.com',
                'role' => 'Admin',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543211',
                'department_id' => $adminDept?->id,
                'designation' => 'Office Administrator',
                'joining_date' => '2026-01-15',
                'status' => 'active'
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'hr@gmail.com',
                'role' => 'HR Manager',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543212',
                'department_id' => $hrDept?->id,
                'designation' => 'HR Lead',
                'joining_date' => '2026-02-01',
                'status' => 'active'
            ],
            [
                'name' => 'Bob Developer',
                'email' => 'emp1@gmail.com',
                'role' => 'Employee',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543213',
                'department_id' => $itDept?->id,
                'designation' => 'Lead Laravel Developer',
                'joining_date' => '2026-02-15',
                'status' => 'active'
            ],
            [
                'name' => 'Charlie Designer',
                'email' => 'emp2@gmail.com',
                'role' => 'Employee',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543214',
                'department_id' => $itDept?->id,
                'designation' => 'UI/UX Designer',
                'joining_date' => '2026-03-01',
                'status' => 'active'
            ],
            [
                'name' => 'David Tester',
                'email' => 'emp3@gmail.com',
                'role' => 'Employee',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543215',
                'department_id' => $itDept?->id,
                'designation' => 'QA Engineer',
                'joining_date' => '2026-03-10',
                'status' => 'active'
            ],
            [
                'name' => 'Emma Writer',
                'email' => 'emp4@gmail.com',
                'role' => 'Employee',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543216',
                'department_id' => $opsDept?->id,
                'designation' => 'Content Writer',
                'joining_date' => '2026-03-20',
                'status' => 'active'
            ],
            [
                'name' => 'Frank Support',
                'email' => 'emp5@gmail.com',
                'role' => 'Employee',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543217',
                'department_id' => $itDept?->id,
                'designation' => 'Tech Support Specialist',
                'joining_date' => '2026-04-01',
                'status' => 'active'
            ],
            [
                'name' => 'Grace Intern',
                'email' => 'intern1@gmail.com',
                'role' => 'Intern',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543218',
                'department_id' => $itDept?->id,
                'designation' => 'Web Dev Intern',
                'joining_date' => '2026-05-01',
                'status' => 'active'
            ],
            [
                'name' => 'Henry Intern',
                'email' => 'intern2@gmail.com',
                'role' => 'Intern',
                'employee_id' => $nextEmployeeId,
                'phone' => '+919876543219',
                'department_id' => $rndDept?->id,
                'designation' => 'R&D Intern',
                'joining_date' => '2026-05-15',
                'status' => 'active'
            ]
        ];

        // 4. Create or Update Users and Assign Roles
        foreach ($users as $userData) {
            $existingUser = User::where('email', $userData['email'])->first();
            $employeeId = ($existingUser && $existingUser->employee_id)
                ? $existingUser->employee_id
                : ($prefix . str_pad($nextSeq++, 3, '0', STR_PAD_LEFT));

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('Raza@StaffHub'),
                    'employee_id' => $employeeId,
                    'phone' => $userData['phone'],
                    'department_id' => $userData['department_id'],
                    'designation' => $userData['designation'],
                    'joining_date' => $userData['joining_date'],
                    'status' => $userData['status'],
                    'signature' => 'signatures/dummy_signature.png',
                ]
            );
            $user->syncRoles([$userData['role']]);
        }
    }
}
