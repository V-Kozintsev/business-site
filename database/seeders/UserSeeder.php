<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder

{
    public function run()
    {
        $adminEmail = 'admin@test.com';
    $admin = User::firstOrCreate(
        ['email' => $adminEmail],
        [
            'name' => 'Admin',
            'password' => bcrypt('password')
        ]
    );
    $admin->assignRole('admin');

    $managerEmail = 'manager@test.com';
    $manager = User::firstOrCreate(
        ['email' => $managerEmail],
        [
            'name' => 'Manager',
            'password' => bcrypt('password')
        ]
    );
    $manager->assignRole('manager');
    }
}
