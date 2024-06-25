<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $manager = Role::create(['name' => 'Manager']);

        $manager->givePermissionTo([
            'view-repo',
            'create-prescription',
            'edit-prescription',
            'show-prescription',
        ]);
    }
}
