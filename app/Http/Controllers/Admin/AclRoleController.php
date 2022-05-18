<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AclRoleController extends Controller
{
	public function index()
	{
		$roles    = Role::all();
		$viewData = [
			'roles' => $roles
		];

		return view('admin.role.index', $viewData);
	}

	public function create()
	{
		$permissions      = Permission::orderBy('group_permission', 'asc')->get();
		$group_permission = config('permission.group_permission') ?? [];
		$viewData         = [
			'permissions' => $permissions,
			'group'       => $group_permission
		];

		return view('admin.role.create', $viewData);
	}

	public function store(Request $request)
	{
		$role              = new Role();
		$role->name        = $request->name;
		$role->guard_name  = $request->guard_name;
		$role->name_slug   = Str::slug($request->name);
		$role->description = $request->description;
		$role->save();

		$role->syncPermissions($request->permissions);
		try {
			if ($permissions = $request->permissions) {
				foreach ($permissions as $item) {
					\DB::table('model_has_permissions')
						->insert([
							'model_type'    => 'App\Models\Admin',
							'permission_id' => $item,
							'model_id'      => get_data_user('admins')
						]);
				}
			}
		} catch (\Exception $exception) {

		}

		return redirect()->back();
	}

	public function edit($id)
	{
		$permissions        = Permission::orderBy('group_permission', 'asc')->get();
		$role               = Role::findOrFail($id);
		$permissions_active = $role->permissions()->pluck('id')->toArray();

		$viewData = [
			'permissions'        => $permissions,
			'role'               => $role,
			'permissions_active' => $permissions_active ?? []
		];

		return view('admin.role.update', $viewData);
	}

	public function update(Request $request, $id)
	{
		$role              = Role::findOrFail($id);
		$role->name        = $request->name;
		$role->name_slug   = Str::slug($request->name);
		$role->guard_name  = $request->guard_name;
		$role->description = $request->description;
		$role->save();

		$role->syncPermissions($request->permissions);
		try {
			if ($permissions = $request->permissions) {
				DB::table('model_has_permissions')
					->where('model_id',get_data_user('admins'))
					->delete();
				foreach ($permissions as $item) {
					\DB::table('model_has_permissions')
						->insert([
							'model_type'    => 'App\Models\Admin',
							'permission_id' => $item,
							'model_id'      => get_data_user('admins')
						]);
				}
			}
		} catch (\Exception $exception) {

		}
//		$allPermissions = Permission::all();//Get all permissions


//		foreach ($allPermissions as $p) {
//			$role->revokePermissionTo($p); //Remove all permissions associated with role
//		}


		return redirect()->back();

	}

	public function delete($id)
	{
		Role::findOrFail($id)->delete();
		return redirect()->back();
	}
}
