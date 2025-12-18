<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * @mixin EloquentBuilder
 * @mixin QueryBuilder
 */
class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /* Grupos */
        $roles = [
            [
                'name' => 'admin',
            ],
            [
                'name' => 'directorship',
            ],
            [
                'name' => 'applicant',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        /* Permissões */
        $permissions = [
            [
                'name' => 'view-own-forms',
            ],
            [
                'name' => 'create-form',
            ],
            [
                'name' => 'view-all-forms',
            ],
            [
                'name' => 'pre-approve-form',
            ],
            [
                'name' => 'reject-form',
            ],
            [
                'name' => 'final-approve-form',
            ],
            [
                'name' => 'final-reject-form',
            ],
        ];

        foreach ($permissions as $permissionData) {
            Permission::updateOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Atualizar permissões das roles SEM afetar usuários
        $admin = Role::where('name', 'admin')->first();
        $director = Role::where('name', 'directorship')->first();
        $applicant = Role::where('name', 'applicant')->first();

        if ($admin) {
            $admin->syncPermissions(['view-all-forms', 'pre-approve-form', 'reject-form']);
        }

        if ($director) {
            $director->syncPermissions(['final-approve-form', 'final-reject-form']);
        }

        if ($applicant) {
            $applicant->syncPermissions(['view-own-forms', 'create-form']);
        }
    }
}
