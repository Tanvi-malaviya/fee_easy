<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'Manage Institutes',
            'Manage Plans',
            'Manage Subscriptions',
            'Manage Payments',
            'Manage WhatsApp Settings',
            'Send Notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions($permissions);

        // Create Admin User
        $admin = User::firstOrCreate([
            'email' => 'admin@feeeasy.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('Admin');
    }
}
