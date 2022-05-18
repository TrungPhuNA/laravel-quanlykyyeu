<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
	use AuthenticatesUsers;

	public function getLoginAdmin(Request $request)
	{
		$data = [
			'email'    => '',
			'password' => ''
		];
		if ($request->preview == "true") {
			$data = [
				'email'    => 'phu.preview@gmail.com',
				'password' => '123456789'
			];
		}
		return view('admin.auth.login', compact('data'));
	}

	public function postLoginAdmin(Request $request)
	{
		if (\Auth::guard('admins')->attempt(['email' => $request->email, 'password' => $request->password])) {
//            return redirect()->intended('/api-admin');
			return redirect()->route('get.admin.index');
		}

		return redirect()->back();
	}

	public function getLogoutAdmin()
	{
		\Auth::guard('admins')->logout();
		return redirect()->to('/');
	}
}
