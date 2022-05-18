<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Requests\AdminRequestAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminAccountController extends Controller
{
    public function index()
    {
		if (!check_admin()) return redirect()->route('get.admin.index');
        $admins = Admin::get();
        $viewData = [
            'admins' => $admins
        ];
        return view('admin.admin.index', $viewData);
    }

    public function create()
    {
		$roles    = Role::all();
        return view('admin.admin.create',compact('roles'));
    }

    public function store(AdminRequestAccount $request)
    {
        $data = $request->except("_token","roles");
        $data['password']   =  Hash::make($data['password']);
        $data['created_at'] = Carbon::now();

        $admin = Admin::insert($data);
		if ($roles = $request->roles) {
			$admin->assignRole($request->roles);
		}
        return redirect()->back();
    }

    public function edit($id)
    {
        $admin = Admin::find($id);
		$roles     = Role::all();
		$roles_old = $admin->roles()->pluck('id')->toArray();
        return view('admin.admin.update', compact('admin','roles','roles_old'));
    }

    public function update(AdminRequestAccount $request, $id)
    {
        $data = $request->except("_token","password","roles");
        $admin = Admin::find($id);
        if ($request->password)
        {
            $admin->password   =  Hash::make($request->password);
        }
		$admin->fill($data)->save();
//		$roles = $request->roles;
		DB::table('model_has_roles')->where('model_id',$id)->delete();
		$admin->assignRole($request->input('roles'));

//		if (isset($roles)) {
//			$admin->roles()->sync($roles);  //If one or more role is selected associate user to roles
//		} else {
//			$admin->roles()->detach(); //If no role is selected remove exisiting role associated to a user
//		}
        return redirect()->back();
    }

    public function delete($id)
    {
        $admin = Admin::find($id);
        if (get_data_user('admins','level') != 1)
		{
			return redirect()->back();
		}

        $admin->delete();
        return redirect()->back();
    }
}
