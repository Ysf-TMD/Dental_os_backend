<?php

namespace Database\Seeders;

use App\Modules\Role\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrateur',
                'description' => 'Accès complet à toutes les fonctionnalités du système',
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrateur',
                'description' => 'Gestion complète de la clinique et des utilisateurs',
                'is_active' => true,
            ],
            [
                'name' => 'dentist',
                'display_name' => 'Dentiste',
                'description' => 'Accès aux fonctionnalités cliniques et patients',
                'is_active' => true,
            ],
            [
                'name' => 'assistant',
                'display_name' => 'Assistant Dentaire',
                'description' => 'Gestion des rendez-vous et patients',
                'is_active' => true,
            ],
            [
                'name' => 'secretary',
                'display_name' => 'Secrétaire',
                'description' => 'Gestion administrative et accueil',
                'is_active' => true,
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Comptable',
                'description' => 'Gestion de la facturation et des paiements',
                'is_active' => true,
            ],
            [
                'name' => 'lab_technician',
                'display_name' => 'Technicien Laboratoire',
                'description' => 'Gestion des commandes laboratoire',
                'is_active' => true,
            ],
            [
                'name' => 'viewer',
                'display_name' => 'Lecteur',
                'description' => 'Accès en lecture seule aux données',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
