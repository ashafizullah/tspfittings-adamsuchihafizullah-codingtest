<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create user
        $user = User::create([
            'name' => 'Administrator',
            'username' => 'administrator',
            'no_hp' => '081234567890',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        //get all permissions
        $permissions = Permission::all();

        //get role admin
        $role = Role::find(1);

        //assign permission to role
        $role->syncPermissions($permissions);

        //assign role to user
        $user->assignRole($role);

        // Create welcome.index permission if it doesn't exist
        Permission::firstOrCreate(['name' => 'welcome.index']);

        // Create Production Manager role
        $productionManagerRole = Role::create(['name' => 'production_manager']);

        // Get all work order permissions
        $productionManagerPermissions = Permission::where('name', 'like', 'work_orders.%')->get();

        // Add welcome.index permission to the collection
        $productionManagerPermissions = $productionManagerPermissions->merge(
            Permission::where('name', 'welcome.index')->get()
        );

        // Assign all permissions to Production Manager role
        $productionManagerRole->syncPermissions($productionManagerPermissions);

        // Create Operator role
        $operatorRole = Role::create(['name' => 'operator']);

        // Get limited permissions for Operator
        $operatorPermissions = Permission::whereIn('name', [
            'work_orders.view_assigned',
            'work_orders.update_status',
            'welcome.index' // Add welcome.index permission for operators
        ])->get();

        // Assign limited permissions to Operator role
        $operatorRole->syncPermissions($operatorPermissions);

        // Create sample Production Manager user
        $productionManager = User::create([
            'name'     => 'Production Manager',
            'username' => 'production.manager',
            'no_hp'    => '081234567891',
            'email'    => 'production.manager@company.com',
            'password' => bcrypt('password'),
        ]);

        // Assign Production Manager role to user
        $productionManager->assignRole($productionManagerRole);

        // Create sample Operator user
        $operator = User::create([
            'name'     => 'Operator',
            'username' => 'operator',
            'no_hp'    => '081234567892',
            'email'    => 'operator@company.com',
            'password' => bcrypt('password'),
        ]);

        // Assign Operator role to user
        $operator->assignRole($operatorRole);

        // If you want to give work order permissions to Admin as well
        // Get the admin role (assuming it exists)
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            // Add work order permissions to admin role
            $adminRole->givePermissionTo($productionManagerPermissions);
        }
    }
}
