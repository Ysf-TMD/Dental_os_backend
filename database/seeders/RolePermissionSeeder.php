<?php

namespace Database\Seeders;

use App\Modules\Permission\Models\Permission;
use App\Modules\Role\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');
      /*  $permissions = Permission::all()->keyBy('name');

        $this->command->info('Found ' . $roles->count() . ' roles');
        $this->command->info('Found ' . $permissions->count() . ' permissions');

        // Super Admin - All permissions
        if ($roles->has('super_admin')) {
            $roles['super_admin']->permissions()->sync($permissions->pluck('id'));
            $this->command->info('Assigned all permissions to super_admin');
        }

        // Admin - All except admin specific
        if ($roles->has('admin')) {
            $adminPermissions = $permissions->filter(function ($permission) {
                return !str_contains($permission->name, 'roles.') &&
                       !str_contains($permission->name, 'permissions.');
            });
            $roles['admin']->permissions()->sync($adminPermissions->pluck('id'));
            $this->command->info('Assigned ' . $adminPermissions->count() . ' permissions to admin');
        }

        // Dentist - Clinical modules
        if ($roles->has('dentist')) {
            $dentistPermissions = $permissions->filter(function ($permission) {
                return str_starts_with($permission->name, 'patients.') ||
                       str_starts_with($permission->name, 'agenda.') ||
                       str_starts_with($permission->name, 'consultations.') ||
                       str_starts_with($permission->name, 'traitements.') ||
                       str_starts_with($permission->name, 'odontogramme.');
            });
            $roles['dentist']->permissions()->sync($dentistPermissions->pluck('id'));
            $this->command->info('Assigned ' . $dentistPermissions->count() . ' permissions to dentist');
        }

        // Assistant - Patients and Agenda
        if ($roles->has('assistant')) {
            $assistantPermissions = $permissions->filter(function ($permission) {
                return str_starts_with($permission->name, 'patients.') ||
                       str_starts_with($permission->name, 'agenda.');
            });
            $roles['assistant']->permissions()->sync($assistantPermissions->pluck('id'));
            $this->command->info('Assigned ' . $assistantPermissions->count() . ' permissions to assistant');
        }

        // Secretary - Patients, Agenda, Facturation
        if ($roles->has('secretary')) {
            $secretaryPermissions = $permissions->filter(function ($permission) {
                return str_starts_with($permission->name, 'patients.') ||
                       str_starts_with($permission->name, 'agenda.') ||
                       str_starts_with($permission->name, 'facturation.');
            });
            $roles['secretary']->permissions()->sync($secretaryPermissions->pluck('id'));
            $this->command->info('Assigned ' . $secretaryPermissions->count() . ' permissions to secretary');
        }

        // Accountant - Finance modules
        if ($roles->has('accountant')) {
            $accountantPermissions = $permissions->filter(function ($permission) {
                return str_starts_with($permission->name, 'facturation.') ||
                       str_starts_with($permission->name, 'paiements.') ||
                       str_starts_with($permission->name, 'devis.');
            });
            $roles['accountant']->permissions()->sync($accountantPermissions->pluck('id'));
            $this->command->info('Assigned ' . $accountantPermissions->count() . ' permissions to accountant');
        }

        // Lab Technician - Lab orders and patients view
        if ($roles->has('lab_technician')) {
            $labPermissions = $permissions->filter(function ($permission) {
                return str_starts_with($permission->name, 'laboratoire.') ||
                       $permission->name === 'patients.view';
            });
            $roles['lab_technician']->permissions()->sync($labPermissions->pluck('id'));
            $this->command->info('Assigned ' . $labPermissions->count() . ' permissions to lab_technician');
        }

        // Viewer - Only view permissions
        if ($roles->has('viewer')) {
            $viewerPermissions = $permissions->filter(function ($permission) {
                return $permission->action === 'view';
            });
            $roles['viewer']->permissions()->sync($viewerPermissions->pluck('id'));
            $this->command->info('Assigned ' . $viewerPermissions->count() . ' permissions to viewer');
        }*/
    }
}
