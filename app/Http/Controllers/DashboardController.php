<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'Kasir') {
            return view('dashboard.kasir');
        } elseif ($user->role->name === 'Manajer') {
            return view('dashboard.manajer');
        } elseif ($user->role->name === 'Admin') {
            return view('dashboard.admin');
        }elseif ($user->role->name === 'Waiter') { 
            return view('dashboard.waiter');
        }

        return redirect()->route('login')->withErrors('Role tidak dikenali.');
    }
}
