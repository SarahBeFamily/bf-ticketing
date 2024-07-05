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
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'edit projects']);
        Permission::create(['name' => 'publish tickets']);
        Permission::create(['name' => 'edit tickets']);
        Permission::create(['name' => 'delete tickets']);
        Permission::create(['name' => 'publish comments']);
        Permission::create(['name' => 'edit comments']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'customer']);
        $role1->givePermissionTo('publish tickets');
        $role1->givePermissionTo('publish comments');

        $role2 = Role::create(['name' => 'team']);
        $role2->givePermissionTo('edit users');
        $role2->givePermissionTo('edit projects');
        $role2->givePermissionTo('publish tickets');
        $role2->givePermissionTo('edit tickets');
        $role2->givePermissionTo('publish comments');
        $role2->givePermissionTo('edit comments');

        $role3 = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Example Customer User',
            'email' => 'test@mail.com',
            'password' => bcrypt('test2024'),
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Sarah Pinna',
            'email' => 'sarah@befamily.it',
            'password' => bcrypt('Spinn@2024'),
        ]);
        $user->assignRole($role2);

        // $user = \App\Models\User::factory()->create([
        //     'name' => 'Sarah Pinna',
        //     'email' => 'sarah@befamily.it',
        // ]);
        // $user->assignRole($role3);
    }
}
