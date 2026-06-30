<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dashboard
        Permission::firstOrCreate(['name' => 'Dashboard']);

        Permission::firstOrCreate(['name' => 'Department Create']);
        Permission::firstOrCreate(['name' => 'Department Edit']);
        Permission::firstOrCreate(['name' => 'Department Delete']);

        // Access Management
        Permission::firstOrCreate(['name' => 'AccessManagement-Index']);
        Permission::firstOrCreate(['name' => 'AccessManagement-Create']);
        Permission::firstOrCreate(['name' => 'AccessManagement-Edit']);
        Permission::firstOrCreate(['name' => 'AccessManagement-View']);
        Permission::firstOrCreate(['name' => 'AccessManagement-Delete']);

        // Create roles if they don't exist
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);

        // Get all permissions
        $permissions = Permission::all();

        // Sync all permissions to Super Admin
        $superAdmin->syncPermissions($permissions);

        // Give Dashboard permission to Admin
        $admin->givePermissionTo([
            'Dashboard',
        ]);
    }
}
