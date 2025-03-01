<?php

namespace App\Http\Controllers\Apps;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Kecamatan;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:users.index')->only('index');
		$this->middleware('permission:users.create')->only('create', 'store');
		$this->middleware('permission:users.edit')->only('edit', 'update');
		$this->middleware('permission:users.delete')->only('destroy');
	}

	public function index()
	{
		$users = User::when(request()->q, function ($users) {
			$users = $users->where('name', 'like', '%' . request()->q . '%');
		})->with('roles')->latest()->paginate(10);

		return Inertia::render('Apps/Users/Index', [
			'users' => $users
		]);
	}

	public function create()
	{
		$roles = Role::all();
		$kecamatans = Kecamatan::all();

		return Inertia::render('Apps/Users/Create', [
			'roles' => $roles,
			'kecamatans' => $kecamatans
		]);
	}

	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required',
			'username' => 'required|unique:users|min:6',
			'no_hp' => 'required|unique:users',
			'email' => 'required|unique:users',
			'password' => 'required|confirmed'
		]);

		$user = User::create([
			'name' => $request->name,
			'username' => $request->username,
			'no_hp' => $request->no_hp,
			'email' => $request->email,
			'password' => bcrypt($request->password)
		]);

		$user->assignRole($request->roles);

		return redirect()->route('apps.users.index');
	}

	public function edit($id)
	{
		$user = User::with('roles')->findOrFail($id);

		$roles = Role::all();
		$kecamatans = Kecamatan::all();

		return Inertia::render('Apps/Users/Edit', [
			'user' => $user,
			'roles' => $roles,
			'kecamatans' => $kecamatans,
		]);
	}

	public function update(Request $request, User $user)
	{
		$this->validate($request, [
			'name' => 'required',
			'username' => 'required|min:6|unique:users,username,' . $user->id,
			'no_hp' => 'required|unique:users,no_hp,' . $user->id,
			'email' => 'required|unique:users,email,' . $user->id,
			'password' => 'nullable|confirmed'
		]);

		if ($request->password == '') {
			$user->update([
				'name' => $request->name,
				'username' => $request->username,
				'no_hp' => $request->no_hp,
				'kecamatan_id' => $request->kecamatan_id,
				'kelurahan_desa_id' => $request->kelurahan_desa_id,
				'email' => $request->email
			]);
		} else {
			$user->update([
				'name' => $request->name,
				'username' => $request->username,
				'email' => $request->email,
				'password' => bcrypt($request->password)
			]);
		}

		$user->syncRoles($request->roles);

		return redirect()->route('apps.users.index');
	}

	public function destroy($id)
	{
		$user = User::findOrFail($id);

		$user->delete();

		return redirect()->route('apps.users.index');
	}
}
