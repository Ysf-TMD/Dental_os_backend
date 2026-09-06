<?php

namespace Database\Seeders;

use App\Modules\Page\Models\Page;
use App\Modules\Role\Models\Role;
use Illuminate\Database\Seeder;

class RolePageSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');
        $pages = Page::all();

        $this->command->info('Found ' . $roles->count() . ' roles');
        $this->command->info('Found ' . $pages->count() . ' pages');

        // Administrator roles - All pages; other roles get none by default
        // so the admin can assign them later through the interface.
        foreach (['super_admin', 'admin'] as $adminRole) {
            if ($roles->has($adminRole)) {
                $roles[$adminRole]->pages()->sync($pages->pluck('id'));
                $this->command->info('Assigned all pages to ' . $adminRole);
            }
        }
    }
}
