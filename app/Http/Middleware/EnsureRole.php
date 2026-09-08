<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (!$user || !$user->isActive()) {
            return redirect()->route('login');
        }
        $roleName = $user->role?->name;
        if (!in_array($roleName, $roles, true)) {
            abort(403, 'Forbidden');
        }
        return $next($request);
    }
}
