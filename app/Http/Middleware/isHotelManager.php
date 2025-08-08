<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isHotelManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role_redirect = $user->role === 'hotel_manager' 
                ? 'dashboard_hotel_manager.dashboard' 
                : ($user->role === 'resepsionis' 
                    ? 'dashboard_resepsionis.dashboard' 
                    : 'dashboard_it_manager.dashboard');
            if ($user->role !== 'hotel_manager') {
                return redirect()->route($role_redirect)
                    ->with('error', 'Akses ditolak. Hanya manajer hotel yang dapat mengakses halaman ini.');
            }
        } else {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        return $next($request);
    }
}
