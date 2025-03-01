<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//permission users
		Permission::create(['name' => 'users.index', 'guard_name' => 'web']);
		Permission::create(['name' => 'users.create', 'guard_name' => 'web']);
		Permission::create(['name' => 'users.edit', 'guard_name' => 'web']);
		Permission::create(['name' => 'users.delete', 'guard_name' => 'web']);

		//permission roles
		Permission::create(['name' => 'roles.index', 'guard_name' => 'web']);
		Permission::create(['name' => 'roles.create', 'guard_name' => 'web']);
		Permission::create(['name' => 'roles.edit', 'guard_name' => 'web']);
		Permission::create(['name' => 'roles.delete', 'guard_name' => 'web']);

		//permission permissions
		Permission::create(['name' => 'permissions.index', 'guard_name' => 'web']);

		//permission welcome
        Permission::create(['name' => 'welcome.index', 'guard_name' => 'web']);

        //permission work_orders
        Permission::create(['name' => 'work_orders.index', 'guard_name' => 'web']);
        Permission::create(['name' => 'work_orders.create', 'guard_name' => 'web']);
        Permission::create(['name' => 'work_orders.edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'work_orders.delete', 'guard_name' => 'web']);
        Permission::create(['name' => 'work_orders.view_assigned', 'guard_name' => 'web']);
        Permission::create(['name' => 'work_orders.update_status', 'guard_name' => 'web']);
        Permission::create(['name' => 'work_orders.view_reports', 'guard_name' => 'web']);
	}
}
