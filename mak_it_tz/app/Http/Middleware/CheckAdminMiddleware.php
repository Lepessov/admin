<?php

namespace App\Http\Middleware;

use App\CONST\AdminRolesCons;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CheckAdminMiddleware
{
    use ApiResponse;

    public function handle($request, Closure $next)
    {
        $user = auth('api')->user();

        if (!$user || $user->role_id !== AdminRolesCons::USER) {
            return $this->errorResponse(null, ResponseAlias::HTTP_METHOD_NOT_ALLOWED, 'Разрешение отколнено!');
        }

        return $next($request);
    }
}
