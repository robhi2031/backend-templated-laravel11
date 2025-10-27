<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => '2031 for Dev',
            'username' => 'admin.dev',
            'password' => bcrypt('123456'),
            'email' => 'robhi.dj@gmail.com',
            'phone_number' => '0',
            'thumb' => 'avatar-sample-01.jpg',
            'user_add' => 1,
        ]);

        $role = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            'user_add' => 1,
        ]);

        // Parent and Childs Menu
        $permissionMenu = Menus::insert([
            'name' => 'Kelola Aplikasi',
            'icon' => 'bi-gear',
            'has_child' => 'Y',
            'is_crud' => 'N',
            'order_line' => '90',
            'user_add' => 1,
        ]);
        $permissionMenu = Menus::insert([
            'name' => 'Informasi Web',
            'icon' => NULL,
            'has_route' => 'Y',
            'route_name' => 'manage_siteinfo',
            'parent_id' => 1,
            'has_child' => 'N',
            'is_crud' => 'Y',
            'order_line' => '90.1',
            'user_add' => 1,
        ]);
        $permissionMenu = Menus::insert([
            'name' => 'Profil Instansi',
            'icon' => NULL,
            'has_route' => 'Y',
            'route_name' => 'manage_profilinstansi',
            'parent_id' => 1,
            'has_child' => 'N',
            'is_crud' => 'Y',
            'order_line' => '90.2',
            'user_add' => 1,
        ]);
        $permissionMenu = Menus::insert([
            'name' => 'Kelola Pengguna',
            'icon' => 'bi-people',
            'has_child' => 'Y',
            'is_crud' => 'N',
            'order_line' => '91',
            'user_add' => 1,
        ]);
        $permissionMenu = Menus::insert([
            'name' => 'Permissions',
            'icon' => NULL,
            'has_route' => 'Y',
            'route_name' => 'manage_permissions',
            'parent_id' => 4,
            'has_child' => 'N',
            'is_crud' => 'Y',
            'order_line' => '91.1',
            'user_add' => 1,
        ]);
        $permissionMenu = Menus::insert([
            'name' => 'Roles',
            'icon' => NULL,
            'has_route' => 'Y',
            'route_name' => 'manage_roles',
            'parent_id' => 4,
            'has_child' => 'N',
            'is_crud' => 'Y',
            'order_line' => '91.2',
            'user_add' => 1,
        ]);
        $permissionMenu = Menus::insert([
            'name' => 'Users',
            'icon' => NULL,
            'has_route' => 'Y',
            'route_name' => 'manage_users',
            'parent_id' => 4,
            'has_child' => 'N',
            'is_crud' => 'Y',
            'order_line' => '91.3',
            'user_add' => 1,
        ]);
        $permissionMenu = Menus::insert([
            'name' => 'Log Aktivitas',
            'icon' => NULL,
            'has_route' => 'Y',
            'route_name' => 'user_activities',
            'parent_id' => 4,
            'has_child' => 'N',
            'is_crud' => 'Y',
            'order_line' => '91.4',
            'user_add' => 1,
        ]);
        // Manajemen App for Permissions
        $permission = Permission::create([
            'name' => 'kelola-aplikasi-read',
            'fid_menu' => 1,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // Informasi Web for Permissions
        $permission = Permission::create([
            'name' => 'kelola-aplikasi-informasi-web-read',
            'fid_menu' => 2,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-aplikasi-informasi-web-update',
            'fid_menu' => 2,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // Profil Instansi for Permissions
        $permission = Permission::create([
            'name' => 'kelola-aplikasi-profil-instansi-read',
            'fid_menu' => 3,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-aplikasi-profil-instansi-update',
            'fid_menu' => 3,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // Manajemen Users for Permissions
        $permission = Permission::create([
            'name' => 'kelola-pengguna-read',
            'fid_menu' => 4,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // Permissions for Permissions
        $permission = Permission::create([
            'name' => 'kelola-pengguna-permissions-read',
            'fid_menu' => 5,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-permissions-create',
            'fid_menu' => 5,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-permissions-update',
            'fid_menu' => 5,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-permissions-delete',
            'fid_menu' => 5,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // Roles for Permissions
        $permission = Permission::create([
            'name' => 'kelola-pengguna-roles-read',
            'fid_menu' => 6,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-roles-create',
            'fid_menu' => 6,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-roles-update',
            'fid_menu' => 6,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-roles-delete',
            'fid_menu' => 6,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // Users for Permissions
        $permission = Permission::create([
            'name' => 'kelola-pengguna-users-read',
            'fid_menu' => 7,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-users-create',
            'fid_menu' => 7,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-users-update',
            'fid_menu' => 7,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-users-delete',
            'fid_menu' => 7,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // Users Activity for Permissions
        $permission = Permission::create([
            'name' => 'kelola-pengguna-log-aktivitas-read',
            'fid_menu' => 8,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        $permission = Permission::create([
            'name' => 'kelola-pengguna-log-aktivitas-delete',
            'fid_menu' => 8,
            'guard_name' => 'web',
            'user_add' => 1,
        ]);
        // $permissions = Permission::pluck('id','id')->all();
        // $role->syncPermissions($permissions);
        $role->givePermissionTo('kelola-aplikasi-read');
        $role->givePermissionTo('kelola-aplikasi-informasi-web-read');
        $role->givePermissionTo('kelola-aplikasi-informasi-web-update');
        $role->givePermissionTo('kelola-aplikasi-profil-instansi-read');
        $role->givePermissionTo('kelola-aplikasi-profil-instansi-update');
        $role->givePermissionTo('kelola-pengguna-read');
        $role->givePermissionTo('kelola-pengguna-permissions-read');
        $role->givePermissionTo('kelola-pengguna-permissions-create');
        $role->givePermissionTo('kelola-pengguna-permissions-update');
        $role->givePermissionTo('kelola-pengguna-permissions-delete');
        $role->givePermissionTo('kelola-pengguna-roles-read');
        $role->givePermissionTo('kelola-pengguna-roles-create');
        $role->givePermissionTo('kelola-pengguna-roles-update');
        $role->givePermissionTo('kelola-pengguna-roles-delete');
        $role->givePermissionTo('kelola-pengguna-users-read');
        $role->givePermissionTo('kelola-pengguna-users-create');
        $role->givePermissionTo('kelola-pengguna-users-update');
        $role->givePermissionTo('kelola-pengguna-users-delete');
        $role->givePermissionTo('kelola-pengguna-log-aktivitas-read');
        $role->givePermissionTo('kelola-pengguna-log-aktivitas-delete');

        $user->assignRole([$role->id]);
        $user->givePermissionTo('kelola-aplikasi-read');
        $user->givePermissionTo('kelola-aplikasi-informasi-web-read');
        $user->givePermissionTo('kelola-aplikasi-informasi-web-update');
        $user->givePermissionTo('kelola-aplikasi-profil-instansi-read');
        $user->givePermissionTo('kelola-aplikasi-profil-instansi-update');
        $user->givePermissionTo('kelola-pengguna-read');
        $user->givePermissionTo('kelola-pengguna-permissions-read');
        $user->givePermissionTo('kelola-pengguna-permissions-create');
        $user->givePermissionTo('kelola-pengguna-permissions-update');
        $user->givePermissionTo('kelola-pengguna-permissions-delete');
        $user->givePermissionTo('kelola-pengguna-roles-read');
        $user->givePermissionTo('kelola-pengguna-roles-create');
        $user->givePermissionTo('kelola-pengguna-roles-update');
        $user->givePermissionTo('kelola-pengguna-roles-delete');
        $user->givePermissionTo('kelola-pengguna-users-read');
        $user->givePermissionTo('kelola-pengguna-users-create');
        $user->givePermissionTo('kelola-pengguna-users-update');
        $user->givePermissionTo('kelola-pengguna-users-delete');
        $user->givePermissionTo('kelola-pengguna-log-aktivitas-read');
        $user->givePermissionTo('kelola-pengguna-log-aktivitas-delete');
    }
}
