<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar cache de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Asegurar que el rol exista
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Crear o actualizar usuario
        $user = User::updateOrCreate(
            ['email' => 'hhugodiaz23@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('hugo123.'),
            ]
        );

        // Asignar rol admin
        $user->syncRoles([$adminRole]);
    }
}
