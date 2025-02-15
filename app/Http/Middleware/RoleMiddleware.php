<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('loginView')->with('error', 'You must log in first.');
        }
        if (Auth::user()->role !== $role) {
            // return abort(403, 'Unauthorized action. You do not have permission to access this page.');
            return redirect()->route('loginView')->with('error', 'Access denied! Admins only.');
        }
        return $next($request);
    }

}
