<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('code', 'admin')->first();
        $department = Department::where('name', 'IT')->first();

        if (!$role || !$department) {
            $this->command->error('Role หรือ Department ยังไม่มีข้อมูล');
            return;
        }

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'role_id' => $role->id,
                'department_id' => $department->id,
            ]
        );

        $this->command->info('Admin user created');
    }
}
