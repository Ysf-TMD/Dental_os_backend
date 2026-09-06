<?php

namespace App\Http\Middleware;

use App\Services\RBACService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPageAccess
{
    protected RBACService $rbacService;

    public function __construct(RBACService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $pagePath = $request->path();
        
        if (!$this->rbacService->checkUserPageAccess($user, $pagePath)) {
            return response()->json(['message' => 'Accès refusé à cette page'], 403);
        }

        return $next($request);
    }
}
