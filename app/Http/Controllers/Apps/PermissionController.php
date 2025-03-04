<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:permissions.index')->only('index');
	}

	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request)
	{
		//get permissions
		$permissions = Permission::when(request()->q, function ($permissions) {
			$permissions = $permissions->where('name', 'like', '%' . request()->q . '%');
		})->latest()->paginate(10);

		//return inertia view
		return inertia('Apps/Permissions/Index', [
			'permissions' => $permissions
		]);
	}
}
