<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
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
        Schema::disableForeignKeyConstraints();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /* Grupos */
        $roles = [
            [
                'name' => 'admin',
            ],
            [
                'name' => 'coordinator',
            ],
            [
                'name' => 'applicant',
            ],
            [
                'name' => 'viewer',
            ],
        ];

        Role::query()->truncate();

        foreach ($roles as $roleData) {
            Role::query()
                ->updateOrCreate(
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

        Permission::query()->truncate();

        foreach ($permissions as $permissionData) {
            Permission::query()
                ->updateOrCreate(
                    ['name' => $permissionData['name']],
                    $permissionData
                );
        }

        // Atualizar permissões das roles SEM afetar usuários
        $admin = Role::query()->where('name', 'admin')->first();
        $coordinator = Role::query()->where('name', 'coordinator')->first();
        $applicant = Role::query()->where('name', 'applicant')->first();

        if ($admin) {
            $admin->syncPermissions(['view-all-forms', 'pre-approve-form', 'reject-form']);
        }

        if ($coordinator) {
            $coordinator->syncPermissions(['final-approve-form', 'final-reject-form']);
        }

        if ($applicant) {
            $applicant->syncPermissions(['view-own-forms', 'create-form']);
        }

        Schema::enableForeignKeyConstraints();
    }
}
