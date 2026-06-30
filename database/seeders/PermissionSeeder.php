<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Cache reset
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===== DASHBOARD =====
        Permission::firstOrCreate(['name' => 'Dashboard']);

        // ===== DEPARTMENT =====
        Permission::firstOrCreate(['name' => 'Department-Index']);
        Permission::firstOrCreate(['name' => 'Department-Create']);
        Permission::firstOrCreate(['name' => 'Department-Edit']);
        Permission::firstOrCreate(['name' => 'Department-Delete']);
        Permission::firstOrCreate(['name' => 'Department-View']);

        // ===== ACCESS MANAGEMENT =====
        Permission::firstOrCreate(['name' => 'AccessManagement-Index']);
        Permission::firstOrCreate(['name' => 'AccessManagement-Create']);
        Permission::firstOrCreate(['name' => 'AccessManagement-Edit']);
        Permission::firstOrCreate(['name' => 'AccessManagement-View']);
        Permission::firstOrCreate(['name' => 'AccessManagement-Delete']);

        // ===== EMPLOYEE MANAGEMENT =====
        Permission::firstOrCreate(['name' => 'Employee-Index']);
        Permission::firstOrCreate(['name' => 'Employee-Create']);
        Permission::firstOrCreate(['name' => 'Employee-Edit']);
        Permission::firstOrCreate(['name' => 'Employee-View']);
        Permission::firstOrCreate(['name' => 'Employee-Delete']);
        Permission::firstOrCreate(['name' => 'Employee-Restore']);
        Permission::firstOrCreate(['name' => 'Employee-ForceDelete']);

        // ===== ATTENDANCE =====
        Permission::firstOrCreate(['name' => 'Attendance-Index']);
        Permission::firstOrCreate(['name' => 'Attendance-Create']);
        Permission::firstOrCreate(['name' => 'Attendance-Edit']);
        Permission::firstOrCreate(['name' => 'Attendance-View']);
        Permission::firstOrCreate(['name' => 'Attendance-Delete']);

        // ===== LEAVE TYPES =====
        Permission::firstOrCreate(['name' => 'LeaveType-Index']);
        Permission::firstOrCreate(['name' => 'LeaveType-Create']);
        Permission::firstOrCreate(['name' => 'LeaveType-Edit']);
        Permission::firstOrCreate(['name' => 'LeaveType-View']);
        Permission::firstOrCreate(['name' => 'LeaveType-Delete']);

        // ===== LEAVES =====
        Permission::firstOrCreate(['name' => 'Leave-Index']);
        Permission::firstOrCreate(['name' => 'Leave-Create']);
        Permission::firstOrCreate(['name' => 'Leave-Edit']);
        Permission::firstOrCreate(['name' => 'Leave-View']);
        Permission::firstOrCreate(['name' => 'Leave-Delete']);
        Permission::firstOrCreate(['name' => 'Leave-Approve']);
        Permission::firstOrCreate(['name' => 'Leave-Reject']);

        // ===== TASKS =====
        Permission::firstOrCreate(['name' => 'Task-Index']);
        Permission::firstOrCreate(['name' => 'Task-Create']);
        Permission::firstOrCreate(['name' => 'Task-Edit']);
        Permission::firstOrCreate(['name' => 'Task-View']);
        Permission::firstOrCreate(['name' => 'Task-Delete']);

        // ===== SETTINGS =====
        Permission::firstOrCreate(['name' => 'Settings-Index']);
        Permission::firstOrCreate(['name' => 'Company-Index']);
        Permission::firstOrCreate(['name' => 'Company-Edit']);

        // ===== ROLES =======
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin      = Role::firstOrCreate(['name' => 'Admin']);

        // Naye roles
        $hrManager   = Role::firstOrCreate(['name' => 'HR Manager']);
        $deptManager = Role::firstOrCreate(['name' => 'Department Manager']);
        $employee    = Role::firstOrCreate(['name' => 'Employee']);

        // Super Admin — sab permissions
        $superAdmin->syncPermissions(Permission::all());

        // Admin — existing same rakha
        $admin->givePermissionTo([
            'Dashboard',
        ]);

        // HR Manager
        $hrManager->syncPermissions([
            'Dashboard',
            'Employee-Index',
            'Employee-Create',
            'Employee-Edit',
            'Employee-View',
            'Employee-Delete',
            'Employee-Restore',
            'Department-Index',
            'Department-View',
            'Attendance-Index',
            'Attendance-Create',
            'Attendance-Edit',
            'Attendance-View',
            'Attendance-Delete',
            'LeaveType-Index',
            'LeaveType-Create',
            'LeaveType-Edit',
            'LeaveType-View',
            'LeaveType-Delete',
            'Leave-Index',
            'Leave-Create',
            'Leave-Edit',
            'Leave-View',
            'Leave-Approve',
            'Leave-Reject',
            'Task-Index',
            'Task-Create',
            'Task-Edit',
            'Task-View',
        ]);

        // Department Manager
        $deptManager->syncPermissions([
            'Dashboard',
            'Employee-Index',
            'Employee-View',
            'Department-Index',
            'Department-View',
            'Attendance-Index',
            'Attendance-Create',
            'Attendance-Edit',
            'Attendance-View',
            'Leave-Index',
            'Leave-View',
            'Leave-Approve',
            'Leave-Reject',
            'Task-Index',
            'Task-Create',
            'Task-Edit',
            'Task-View',
            'Task-Delete',
        ]);

        // Employee
        $employee->syncPermissions([
            'Dashboard',
            'Employee-View',
            'Attendance-Index',
            'Attendance-View',
            'Leave-Index',
            'Leave-Create',
            'Leave-View',
            'Task-Index',
            'Task-View',
        ]);

        $this->command->info('All permissions seeded successfully.');
        $this->command->info('Roles assigned: Super Admin, Admin, HR Manager, Department Manager, Employee.');
    }
}
