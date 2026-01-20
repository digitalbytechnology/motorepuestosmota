<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $vendedor = Role::firstOrCreate(['name' => 'vendedor']);
        $mecanico = Role::firstOrCreate(['name' => 'mecanico']);

        // Asignar rol admin al usuario admin por email
        $user = User::where('email', 'admin@gmail.com')->first();
        if ($user) {
            $user->syncRoles(['admin']);
        }
    }
}
