<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return view('layouts.dashboard');
    }

    public function employee()
    {
        return view('layouts.home');
    }

    public function home()
    {
        $role = Auth::user()->role;
        $adminRoles = array('moderator', 'superadmin');
        $allRoles = array('moderator', 'superadmin', 'normal');
        if(in_array($role, $adminRoles)){
            return redirect('d/admin/medias');
        }elseif (in_array($role, $allRoles)) {
            return redirect('e/feed');
        }else{
            return view('/login');
        }
    }
}
