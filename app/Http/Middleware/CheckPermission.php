<?php

namespace App\Http\Middleware;

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
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = $request->user() ?? auth('admin')->user() ?? auth('employee')->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // If user is Admin (has role 'admin' or logged in via admin guard) OR has the specific permission
        $isAdmin = auth('admin')->check() || (method_exists($user, 'hasRole') && $user->hasRole('admin'));

        if ($isAdmin || $user->can($permission)) {
            return $next($request);
        }

        abort(403, 'Unauthorized Access - You do not have the required permission: ' . $permission);
    }
}
