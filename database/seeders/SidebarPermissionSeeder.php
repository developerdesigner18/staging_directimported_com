<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SidebarPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard',
            'users',
            'employee',
            'manage_information',
            'bookings',
            'slider',
            'accessories_equipments',
            'location',
            'gallery',
            'newsletters',
            'manage_information_extra',
            'bikes',
            'tours',
            'system_settings',
            'emails',
        ];

        // -----------------------------
        // Admin Permissions & Role
        // -----------------------------
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'admin',
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'admin',
        ]);

        $adminRole->syncPermissions($permissions);

        $admin = Admin::first(); // ya Admin::find(1)
        if ($admin) {
            $admin->assignRole('admin');
        }

        // -----------------------------
        // Employee Permissions & Role
        // -----------------------------
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'employee',
            ]);
        }

        $employeeRole = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'employee',
        ]);

        $employeeRole->syncPermissions($permissions);

        $employee = Employee::first(); // agar koi employee hai
        if ($employee) {
            $employee->assignRole('employee');
        }
    }
}
