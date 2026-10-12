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
            'contact_requests',
            'cars',
            'services',
            'about_us',
            'blogs',
            'users',
            'employee',
            'gallery',
            'slider',
            'manage_information',

            'emails',
            'system_settings',
        ];

        // Clean up obsolete permissions if they exist
        $obsolete = [
            'bikes',
            'tours',
            'bookings',
            'manage_information_extra',
            'accessories_equipments',
            'location',
        ];
        Permission::whereIn('name', $obsolete)->delete();

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

        $adminRole->syncPermissions(Permission::where('guard_name', 'admin')->get());

        $admins = Admin::all();
        foreach ($admins as $admin) {
            if (!$admin->hasRole('admin')) {
                $admin->assignRole('admin');
            }
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

        $employeeRole->syncPermissions(Permission::where('guard_name', 'employee')->get());
    }
}
