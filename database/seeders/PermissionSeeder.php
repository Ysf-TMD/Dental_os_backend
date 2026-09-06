<?php

namespace Database\Seeders;

use App\Modules\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'Dashboard' => 'dashboard',
            'Agenda' => 'agenda',
            'Patients' => 'patients',
            'Odontogramme' => 'odontogramme',
            'Consultations' => 'consultations',
            'Traitements' => 'traitements',
            'Laboratoire' => 'laboratoire',
            'Facturation' => 'facturation',
            'Paiements' => 'paiements',
            'Devis' => 'devis',
            'Stock' => 'stock',
            'Employés' => 'employes',
            'Rapports' => 'rapports',
            'CRM' => 'crm',
            'Notifications' => 'notifications',
            'Paramètres' => 'parametres',
            'Rôles' => 'roles',
            'Permissions' => 'permissions',
            'Pages' => 'pages',
            'Affectation Rôles/Permissions' => 'role_permissions',
            'Affectation Rôles/Pages' => 'role_pages',
        ];

        $actions = ['view', 'create', 'update', 'delete'];

        foreach ($modules as $displayName => $moduleName) {
            foreach ($actions as $action) {
                $actionDisplay = [
                    'view' => 'Voir',
                    'create' => 'Créer',
                    'update' => 'Modifier',
                    'delete' => 'Supprimer',
                ];

                Permission::create([
                    'name' => "{$moduleName}.{$action}",
                    'display_name' => "{$actionDisplay[$action]} {$displayName}",
                    'action' => $action,
                    'group_name' => $displayName,
                    'description' => "Permission pour {$actionDisplay[$action]} les {$displayName}",
                ]);
            }
        }
    }
}
