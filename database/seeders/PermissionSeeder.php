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
        Permission::firstOrCreate(['name' => 'Department-Restore']);
        Permission::firstOrCreate(['name' => 'Department-ForceDelete']);

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

        // ===== Profile =====
        Permission::firstOrCreate(['name' => 'Employee-Profile-Edit']);
        Permission::firstOrCreate(['name' => 'Employee-Profile-Index']);

        // ===== ATTENDANCE =====
        Permission::firstOrCreate(['name' => 'Attendance-Index']);
        Permission::firstOrCreate(['name' => 'Attendance-Marking']);
        // Permission::firstOrCreate(['name' => 'Attendance-View']);

        // ===== LEAVE TYPES =====
        Permission::firstOrCreate(['name' => 'LeaveType-Index']);
        Permission::firstOrCreate(['name' => 'LeaveType-Create']);
        Permission::firstOrCreate(['name' => 'LeaveType-Edit']);
        // Permission::firstOrCreate(['name' => 'LeaveType-View']);
        Permission::firstOrCreate(['name' => 'LeaveType-Delete']);

        // ===== LEAVES =====
        Permission::firstOrCreate(['name' => 'Leave-Index']);
        Permission::firstOrCreate(['name' => 'Leave-Apply']);
        // Permission::firstOrCreate(['name' => 'Leave-View']);
        Permission::firstOrCreate(['name' => 'Leave-ApproveReject']);

        // ===== TASKS =====
        Permission::firstOrCreate(['name' => 'Task-Index']);
        Permission::firstOrCreate(['name' => 'Task-Create']);
        Permission::firstOrCreate(['name' => 'Task-Edit']);
        Permission::firstOrCreate(['name' => 'Task-View']);
        Permission::firstOrCreate(['name' => 'Task-Delete']);
        Permission::firstOrCreate(['name' => 'Task-ProgressUpdate']);
        Permission::firstOrCreate(['name' => 'Task-Comment']);
        Permission::firstOrCreate(['name' => 'Task-Document']);

        // ===== REPORTS =====
        Permission::firstOrCreate(['name' => 'Employees-Report']);
        Permission::firstOrCreate(['name' => 'Departments-Report']);
        Permission::firstOrCreate(['name' => 'Attendance-Report']);
        Permission::firstOrCreate(['name' => 'LeaveTypes-Report']);
        Permission::firstOrCreate(['name' => 'Leaves-Report']);
        Permission::firstOrCreate(['name' => 'Tasks-Report']);

        // ===== SETTINGS =====
        Permission::firstOrCreate(['name' => 'Settings-Index']);
        Permission::firstOrCreate(['name' => 'Company-Index']);
        Permission::firstOrCreate(['name' => 'Company-Edit']);

        // ===== HOLIDAYS =====
        Permission::firstOrCreate(['name' => 'Holiday-Index']);
        Permission::firstOrCreate(['name' => 'Holiday-Create']);
        Permission::firstOrCreate(['name' => 'Holiday-Edit']);
        Permission::firstOrCreate(['name' => 'Holiday-View']);
        Permission::firstOrCreate(['name' => 'Holiday-Delete']);
        Permission::firstOrCreate(['name' => 'Holiday-Restore']);
        Permission::firstOrCreate(['name' => 'Holiday-ForceDelete']);

        // ===== PAYROLL =====
        Permission::firstOrCreate(['name' => 'Salary-View']);
        Permission::firstOrCreate(['name' => 'Salary-Edit']);
        Permission::firstOrCreate(['name' => 'Payslip-Index']);
        Permission::firstOrCreate(['name' => 'Payslip-Create']);
        Permission::firstOrCreate(['name' => 'Payslip-Edit']);
        Permission::firstOrCreate(['name' => 'Payslip-View']);
        Permission::firstOrCreate(['name' => 'Payslip-Delete']);

        // ===== ANNOUNCEMENTS =====
        Permission::firstOrCreate(['name' => 'Announcement-Index']);
        Permission::firstOrCreate(['name' => 'Announcement-Create']);
        Permission::firstOrCreate(['name' => 'Announcement-Edit']);
        Permission::firstOrCreate(['name' => 'Announcement-View']);
        Permission::firstOrCreate(['name' => 'Announcement-Delete']);
        Permission::firstOrCreate(['name' => 'Announcement-Restore']);
        Permission::firstOrCreate(['name' => 'Announcement-ForceDelete']);

        // ===== ROLES =======
        $superAdmin  = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin       = Role::firstOrCreate(['name' => 'Admin']);
        $hrManager   = Role::firstOrCreate(['name' => 'HR Manager']);
        $deptManager = Role::firstOrCreate(['name' => 'Department Manager']);
        $employee    = Role::firstOrCreate(['name' => 'Employee']);
        $intern      = Role::firstOrCreate(['name' => 'Intern']);

        // Super Admin — all permissions
        $permissions = Permission::all();
        $superAdmin->syncPermissions($permissions);

        // Admin — almost all permissions except force delete and settings access
        $admin->syncPermissions([
            'Dashboard',
            'Department-Index',
            'Department-Create',
            'Department-Edit',
            'Department-Delete',
            'Department-View',
            'Department-Restore',
            'AccessManagement-Index',
            'AccessManagement-Create',
            'AccessManagement-Edit',
            'AccessManagement-View',
            'AccessManagement-Delete',
            'Employee-Index',
            'Employee-Create',
            'Employee-Edit',
            'Employee-View',
            'Employee-Delete',
            'Employee-Restore',
            'Employee-Profile-Edit',
            'Employee-Profile-Index',
            'Attendance-Index',
            'Attendance-Marking',
            'LeaveType-Index',
            'LeaveType-Create',
            'LeaveType-Edit',
            'LeaveType-Delete',
            'Leave-Index',
            'Leave-Apply',
            'Leave-ApproveReject',
            'Task-Index',
            'Task-Create',
            'Task-Edit',
            'Task-View',
            'Task-Delete',
            'Task-ProgressUpdate',
            'Task-Comment',
            'Task-Document',
            'Employees-Report',
            'Departments-Report',
            'Attendance-Report',
            'LeaveTypes-Report',
            'Leaves-Report',
            'Tasks-Report',
            'Settings-Index',
            'Company-Index',
            'Company-Edit',
            'Holiday-Index',
            'Holiday-Create',
            'Holiday-Edit',
            'Holiday-View',
            'Holiday-Delete',
            'Holiday-Restore',
            'Holiday-ForceDelete',
            'Salary-View',
            'Salary-Edit',
            'Payslip-Index',
            'Payslip-Create',
            'Payslip-Edit',
            'Payslip-View',
            'Payslip-Delete',
            'Announcement-Index',
            'Announcement-Create',
            'Announcement-Edit',
            'Announcement-View',
            'Announcement-Delete',
            'Announcement-Restore',
            'Announcement-ForceDelete',
        ]);

        // HR Manager — department, employee, leaves and attendance management
        $hrManager->syncPermissions([
            'Dashboard',
            'Department-Index',
            'Department-Create',
            'Department-Edit',
            'Department-Delete',
            'Department-View',
            'Department-Restore',
            'Employee-Index',
            'Employee-Create',
            'Employee-Edit',
            'Employee-View',
            'Employee-Delete',
            'Employee-Restore',
            'Employee-Profile-Edit',
            'Employee-Profile-Index',
            'Attendance-Index',
            'Attendance-Marking',
            'LeaveType-Index',
            'LeaveType-Create',
            'LeaveType-Edit',
            'LeaveType-Delete',
            'Leave-Index',
            'Leave-Apply',
            'Leave-ApproveReject',
            'Task-Index',
            'Task-View',
            'Task-Comment',
            'Task-Document',
            'Employees-Report',
            'Departments-Report',
            'Attendance-Report',
            'LeaveTypes-Report',
            'Leaves-Report',
            'Tasks-Report',
            'Holiday-Index',
            'Holiday-Create',
            'Holiday-Edit',
            'Holiday-View',
            'Holiday-Delete',
            'Holiday-Restore',
            'Salary-View',
            'Salary-Edit',
            'Payslip-Index',
            'Payslip-Create',
            'Payslip-Edit',
            'Payslip-View',
            'Payslip-Delete',
            'Announcement-Index',
            'Announcement-Create',
            'Announcement-Edit',
            'Announcement-View',
            'Announcement-Delete',
            'Announcement-Restore',
            'Announcement-ForceDelete',
        ]);

        // Department Manager — view own department, handle leaves, and manage tasks
        $deptManager->syncPermissions([
            'Dashboard',
            'Department-Index',
            'Department-View',
            'Employee-Index',
            'Employee-View',
            'Employee-Profile-Edit',
            'Employee-Profile-Index',
            'Attendance-Index',
            'Attendance-Marking',
            'Leave-Index',
            'Leave-Apply',
            'Leave-ApproveReject',
            'Task-Index',
            'Task-Create',
            'Task-Edit',
            'Task-View',
            'Task-Delete',
            'Task-ProgressUpdate',
            'Task-Comment',
            'Task-Document',
            'Attendance-Report',
            'Leaves-Report',
            'Tasks-Report',
            'Holiday-Index',
            'Holiday-View',
            'Payslip-Index',
            'Payslip-View',
            'Announcement-Index',
            'Announcement-View',
        ]);

        // Employee — self operations (profile, mark attendance, apply leaves, work on tasks)
        $employee->syncPermissions([
            'Dashboard',
            'Employee-Profile-Edit',
            'Employee-Profile-Index',
            'Attendance-Index',
            'Attendance-Marking',
            'Leave-Index',
            'Leave-Apply',
            'Task-Index',
            'Task-View',
            'Task-ProgressUpdate',
            'Task-Comment',
            'Task-Document',
            'Holiday-Index',
            'Holiday-View',
            'Payslip-Index',
            'Payslip-View',
            'Announcement-Index',
            'Announcement-View',
        ]);

        // Intern — self operations (profile, mark attendance, apply leaves, work on tasks)
        $intern->syncPermissions([
            'Dashboard',
            'Employee-Profile-Edit',
            'Employee-Profile-Index',
            'Attendance-Marking',
            'Leave-Index',
            'Leave-Apply',
            'Task-Index',
            'Task-View',
            'Task-ProgressUpdate',
            'Task-Comment',
            'Task-Document',
            'Holiday-Index',
            'Holiday-View',
            'Payslip-Index',
            'Payslip-View',
            'Announcement-Index',
            'Announcement-View',
        ]);

        $this->command->info('All permissions seeded successfully.');
        $this->command->info('Roles assigned: Super Admin, Admin, HR Manager, Department Manager, Employee, Intern.');
    }
}
