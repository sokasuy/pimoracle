<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use App\Models\wf_notification;

class HomeController extends Controller
{
    //

    public function index()
    {
        // $hasReadPermission = Permission::where('role', Auth::user()->role)
        //     ->where('menu_group', 'dashboard')
        //     ->where('view', 'home')
        //     ->where('read', true)
        //     ->exists();
        $hasReadPermission = Permission::checkPermission(Auth::user()->role, 'dashboard', 'home', 'home', 'read');
        return view('home', compact('hasReadPermission'));
        // return view('home', ['hasReadPermission' => $hasReadPermission]);
    }

    public function getWorklist(Request $request)
    {
        $employeeId = $request->get('employee_id');
    }
}
