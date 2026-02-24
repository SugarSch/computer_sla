<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [

            'user' => [
                'repair.create',
                'repair.cancel',
                'repair.upload'
            ],

            'first_line_technician' => [
                'repair.accept',
                'repair.update',
                'repair.forward',
            ],

            'senior_technician' => [
                'repair.update',
                'repair.return',
                'repair.request_equipment',
            ],

            'it_manager' => [
                'equipment.approve',
                'equipment.reject',
                'repair.assign',
                'repair.set_priority',
            ],

            'admin' => [
                'system.manage',
            ],
        ];

        foreach ($map as $roleName => $permissionCodes) {
            $role = Role::where('code', $roleName)->first();

            if (! $role) continue;

            $permissionIds = Permission::whereIn('code', $permissionCodes)
                ->pluck('id');

            $role->permission()->sync($permissionIds);
        }
    }
}
