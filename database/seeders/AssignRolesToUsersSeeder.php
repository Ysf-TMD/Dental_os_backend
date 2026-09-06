<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Role\Models\Role;
use Illuminate\Database\Seeder;

class AssignRolesToUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Get the super_admin role
        $superAdminRole = Role::where('name', 'super_admin')->first();
        
        if (!$superAdminRole) {
            $this->command->error('Super admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Assign super_admin role to all existing users
        $users = User::all();
        
        foreach ($users as $user) {
            // Check if user already has this role
            if (!$user->roles()->where('role_id', $superAdminRole->id)->exists()) {
                $user->roles()->attach($superAdminRole->id);
                $this->command->info("Assigned super_admin role to user: {$user->email}");
            } else {
                $this->command->info("User {$user->email} already has super_admin role");
            }
        }

        $this->command->info('Successfully assigned roles to users.');
    }
}
