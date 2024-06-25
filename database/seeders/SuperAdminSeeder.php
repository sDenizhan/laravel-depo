<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'Suleyman Denizhan',
            'email' => 'suleyman.denizhan@gmail.com',
            'password' => Hash::make('suleyman1234')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Demo Manager User
        $admin = User::create([
            'name' => 'Demo Manager',
            'email' => 'demo-manager@gmail.com',
            'password' => Hash::make('demo12345')
        ]);
        $admin->assignRole('Manager');
    }
}
