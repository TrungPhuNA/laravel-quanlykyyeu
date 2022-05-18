<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
//		activity()->performedOn(Admin::class)
//			->causedBy(get_data_user('admins'))->log('Look mum, I logged something');
    	return redirect()->route('admin.statistical');
    }
}
