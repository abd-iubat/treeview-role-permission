<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        for($i = 0; $i <= 150000; $i++) {
//            try {
//                factory(App\User::class, 1)->create();
//            } catch (\Exception $e) {
//
//            }
//        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['parent_id'=>10, 'order' => 1, 'name' => 'edit articles','description' => 'Edit Article']);
        Permission::create(['parent_id'=>10, 'order' => 2, 'name' => 'delete articles', 'description' => 'delete articles']);
        Permission::create(['parent_id'=>10, 'order' => 3, 'name' => 'publish articles', 'description' => 'publish articles']);
        Permission::create(['parent_id'=>10, 'order' => 4, 'name' => 'unpublish articles', 'description' => 'unpublish articles']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo('edit articles');

        // or may be done by chaining
        $role = Role::create(['name' => 'moderator'])
            ->givePermissionTo(['publish articles', 'unpublish articles']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
