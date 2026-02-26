<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\DailyReport;  // ← ДОБАВЬ
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Employee; 

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

        $adminEmployee = \App\Models\Employee::firstOrCreate(
    ['name' => 'Админ Иванов'], 
    ['role' => 'admin']  
);
$managerEmployee = \App\Models\Employee::firstOrCreate(
    ['name' => 'Менеджер Петров'],
    ['role' => 'manager']  
);

DailyReport::create([
    'employee_id' => $adminEmployee->id,
    'employee_name' => 'Админ Иванов',
    'sales_point' => 'Москва Центр',
    'revenue' => 150000.00,
    'report_date' => now()->subDays(1)
]);

DailyReport::create([
    'employee_id' => $managerEmployee->id,
    'employee_name' => 'Менеджер Петров',
    'sales_point' => 'СПб Невский',
    'revenue' => 95000.00,
    'report_date' => now()
]);
    }
}
