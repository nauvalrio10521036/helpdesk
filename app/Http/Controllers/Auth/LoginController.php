<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'hotel_manager') {
                return redirect()->route('dashboard_hotel_manager.dashboard');
            } elseif ($user->role === 'resepsionis') {
                return redirect()->route('dashboard_resepsionis.dashboard');
            } else {
                return redirect()->route('dashboard_it_manager.dashboard');
            }
        }
        return back()->with('error', 'Username atau password salah!');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('username', $request->username)->first();
    //     if ($user && Hash::check($request->password, $user->password)) {
    //         session(['user_id' => $user->user_id, 'username' => $user->username, 'role' => $user->role]);
            
    //         // Redirect berdasarkan role
    //         if ($user->role === 'hotel_manager') {
    //             return redirect()->route('dashboard_hotel_manager.dashboard');
    //         } elseif ($user->role === 'resepsionis') {
    //             return redirect()->route('dashboard_resepsionis.dashboard');
    //         } else {
    //             return redirect()->route('dashboard_it_manager.dashboard');
    //         }
    //     }
    //     return back()->with('error', 'Username atau password salah!');
    // }

    public function logout()
    {
        // session()->flush();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }
}
