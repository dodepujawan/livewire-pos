<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If user is not authenticated, let auth middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Get current route name
        $routeName = $request->route()?->getName();

        // If route has no name, allow access (edge case)
        if (!$routeName) {
            return $next($request);
        }

        // Find menu entry for this route
        $menu = Menu::where('route_name', $routeName)->first();

        // If no menu entry exists, allow access (route not managed by auth system)
        if (!$menu) {
            return $next($request);
        }

        // If menu has no permission requirement, allow access
        if (empty($menu->permission_name)) {
            return $next($request);
        }

        // Check if user has the required permission using Spatie API
        if (!$user->can($menu->permission_name)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
