<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the route is an admin route but NOT 'admin.login'
        if ($request->routeIs('admin.*') && !$request->routeIs('admin.login') && !$request->routeIs('admin.logout') && !$request->routeIs('admin.store-login') && !$request->routeIs('admin.password.request')) {
            // Check if admin is logged in
            if (!auth()->guard('admin')->check()) {
                return redirect()->route('admin.login');
            }
        }

        return $next($request);
    }
}
