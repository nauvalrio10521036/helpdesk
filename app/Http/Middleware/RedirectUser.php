<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectUser
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
            if ($user->role === 'hotel_manager') {
                return redirect()->route('dashboard_hotel_manager.dashboard');
            } elseif ($user->role === 'resepsionis') {
                return redirect()->route('dashboard_resepsionis.dashboard');
            } else {
                return redirect()->route('dashboard_it_manager.dashboard');
            }
        }
        return $next($request);
    }
}
