<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // RBAC Seeders
        $this->call([
            RoleSeeder::class,
            //PermissionSeeder::class,
            PageSeeder::class,
            RolePermissionSeeder::class,
            RolePageSeeder::class,
        ]);

        // Demo data
        $this->call(DemoSeeder::class);

        // Patients
        $this->call(PatientSeeder::class);
    }
}
