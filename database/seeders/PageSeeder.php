<?php

namespace Database\Seeders;

use App\Modules\Page\Models\Page;
use App\Modules\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            // Pilotage
            [
                'name' => 'dashboard',
                'display_name' => 'Dashboard',
                'path' => '/',
                'component' => 'Dashboard',
                'icon' => 'LayoutDashboard',
                'group_name' => 'Pilotage',
                'is_active' => true,
                'permission_name' => 'dashboard.view',
            ],
            [
                'name' => 'agenda',
                'display_name' => 'Agenda',
                'path' => '/agenda',
                'component' => 'Agenda',
                'icon' => 'Calendar',
                'group_name' => 'Pilotage',
                'is_active' => true,
                'permission_name' => 'agenda.view',
            ],
            // Clinique
            [
                'name' => 'patients',
                'display_name' => 'Patients',
                'path' => '/patients',
                'component' => 'Patients',
                'icon' => 'Users',
                'group_name' => 'Clinique',
                'is_active' => true,
                'permission_name' => 'patients.view',
            ],
            [
                'name' => 'odontogramme',
                'display_name' => 'Odontogramme',
                'path' => '/odontogramme',
                'component' => 'Odontogramme',
                'icon' => 'Activity',
                'group_name' => 'Clinique',
                'is_active' => true,
                'permission_name' => 'odontogramme.view',
            ],
            [
                'name' => 'consultations',
                'display_name' => 'Consultations',
                'path' => '/consultations',
                'component' => 'Consultations',
                'icon' => 'Stethoscope',
                'group_name' => 'Clinique',
                'is_active' => true,
                'permission_name' => 'consultations.view',
            ],
            [
                'name' => 'traitements',
                'display_name' => 'Traitements',
                'path' => '/traitements',
                'component' => 'Traitements',
                'icon' => 'ClipboardList',
                'group_name' => 'Clinique',
                'is_active' => true,
                'permission_name' => 'traitements.view',
            ],
            [
                'name' => 'laboratoire',
                'display_name' => 'Laboratoire',
                'path' => '/laboratoire',
                'component' => 'Laboratoire',
                'icon' => 'FlaskConical',
                'group_name' => 'Clinique',
                'is_active' => true,
                'permission_name' => 'laboratoire.view',
            ],
            // Finance
            [
                'name' => 'facturation',
                'display_name' => 'Facturation',
                'path' => '/facturation',
                'component' => 'Facturation',
                'icon' => 'FileText',
                'group_name' => 'Finance',
                'is_active' => true,
                'permission_name' => 'facturation.view',
            ],
            [
                'name' => 'paiements',
                'display_name' => 'Paiements',
                'path' => '/paiements',
                'component' => 'Paiements',
                'icon' => 'CreditCard',
                'group_name' => 'Finance',
                'is_active' => true,
                'permission_name' => 'paiements.view',
            ],
            [
                'name' => 'devis',
                'display_name' => 'Devis',
                'path' => '/devis',
                'component' => 'Devis',
                'icon' => 'FileSpreadsheet',
                'group_name' => 'Finance',
                'is_active' => true,
                'permission_name' => 'devis.view',
            ],
            // Gestion
            [
                'name' => 'stock',
                'display_name' => 'Stock',
                'path' => '/stock',
                'component' => 'Stock',
                'icon' => 'Package',
                'group_name' => 'Gestion',
                'is_active' => true,
                'permission_name' => 'stock.view',
            ],
            [
                'name' => 'employes',
                'display_name' => 'Employés',
                'path' => '/employes',
                'component' => 'Employes',
                'icon' => 'UserCog',
                'group_name' => 'Gestion',
                'is_active' => true,
                'permission_name' => 'employes.view',
            ],
            [
                'name' => 'rapports',
                'display_name' => 'Rapports',
                'path' => '/rapports',
                'component' => 'Rapports',
                'icon' => 'BarChart3',
                'group_name' => 'Gestion',
                'is_active' => true,
                'permission_name' => 'rapports.view',
            ],
            [
                'name' => 'crm',
                'display_name' => 'CRM',
                'path' => '/crm',
                'component' => 'CRM',
                'icon' => 'Heart',
                'group_name' => 'Gestion',
                'is_active' => true,
                'permission_name' => 'crm.view',
            ],
            [
                'name' => 'notifications',
                'display_name' => 'Notifications',
                'path' => '/notifications',
                'component' => 'Notifications',
                'icon' => 'Bell',
                'group_name' => 'Gestion',
                'is_active' => true,
                'permission_name' => 'notifications.view',
            ],
            [
                'name' => 'parametres',
                'display_name' => 'Paramètres',
                'path' => '/parametres',
                'component' => 'Parametres',
                'icon' => 'Settings',
                'group_name' => 'Gestion',
                'is_active' => true,
                'permission_name' => 'parametres.view',
            ],
            // Administration
            [
                'name' => 'admin_roles',
                'display_name' => 'Rôles',
                'path' => '/admin/roles',
                'component' => 'AdminRoles',
                'icon' => 'Shield',
                'group_name' => 'Administration',
                'is_active' => true,
                'permission_name' => 'roles.view',
            ],
            [
                'name' => 'admin_permissions',
                'display_name' => 'Permissions',
                'path' => '/admin/permissions',
                'component' => 'AdminPermissions',
                'icon' => 'Key',
                'group_name' => 'Administration',
                'is_active' => true,
                'permission_name' => 'permissions.view',
            ],
            [
                'name' => 'admin_pages',
                'display_name' => 'Pages',
                'path' => '/admin/pages',
                'component' => 'AdminPages',
                'icon' => 'Layout',
                'group_name' => 'Administration',
                'is_active' => true,
                'permission_name' => 'pages.view',
            ],
            [
                'name' => 'admin_role_permissions',
                'display_name' => 'Affectation Rôles/Permissions',
                'path' => '/admin/role-permissions',
                'component' => 'AdminRolePermissions',
                'icon' => 'Shield',
                'group_name' => 'Administration',
                'is_active' => true,
                'permission_name' => 'role_permissions.view',
            ],
            [
                'name' => 'admin_role_pages',
                'display_name' => 'Affectation Rôles/Pages',
                'path' => '/admin/role-pages',
                'component' => 'AdminRolePages',
                'icon' => 'Layout',
                'group_name' => 'Administration',
                'is_active' => true,
                'permission_name' => 'role_pages.view',
            ],
        ];

        foreach ($pages as $pageData) {
            $permissionName = $pageData['permission_name'];
            unset($pageData['permission_name']);

            $page = Page::create($pageData);

            // Associate with the corresponding permission and update permission's page_id
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $permission->update(['page_id' => $page->id]);
                $page->permissions()->attach($permission->id);
            }
        }
    }
}
