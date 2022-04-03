<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::create(['name' => 'Icarus']);

        $permissions = [
            'permission_access',
            'permission_show',
            'permission_create',
            'permission_edit',
            'permission_delete',
            'role_access',
            'role_show',
            'role_create',
            'role_edit',
            'role_delete',
            'user_access',
            'user_show',
            'user_create',
            'user_edit',
            'user_delete',
            'course_access',
            'course_show',
            'course_create',
            'course_edit',
            'course_delete',
            'timing_access',
            'timing_show',
            'timing_create',
            'timing_edit',
            'timing_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $tutorRole = Role::create(['name' => 'Tutor']);

        $tutorPermissions = [
            'course_access',
            'course_show',
            'course_create',
            'course_edit',
            'course_delete',
            'timing_access',
            'timing_show',
            'timing_create',
            'timing_edit',
            'timing_delete',
        ];

        foreach ($tutorPermissions as $permission) {
            $tutorRole->givePermissionTo($permission);
        }

        $assistantRole = Role::create(['name' => 'Assistant']);

        $assistantPermissions = [
            'course_access',
            'course_show',
            'course_edit',
            'timing_access',
            'timing_show',
            'timing_edit',
        ];

        foreach ($assistantPermissions as $permission) {
            $assistantRole->givePermissionTo($permission);
        }

        $studentPermissions = [
            'course_access',
            'course_show',
            'timing_access',
            'timing_show',
        ];

        $studentRole = Role::create(['name' => 'Student']);

        foreach ($studentPermissions as $permission) {
            $studentRole->givePermissionTo($permission);
        }
    }
}
