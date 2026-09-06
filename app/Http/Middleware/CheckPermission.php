<?php

namespace App\Http\Middleware;

use App\Services\RBACService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    protected RBACService $rbacService;

    public function __construct(RBACService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        if (!$this->rbacService->checkUserPermission($user, $permission)) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        return $next($request);
    }
}
