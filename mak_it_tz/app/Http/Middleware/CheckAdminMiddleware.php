<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminMiddleware
{
    use ApiResponse;

    public function handle($request, Closure $next)
    {
        $user = auth('api')->user();

        if (!$user || $user->role_id !== 1) {
            return $this->errorResponse(null, Response::HTTP_METHOD_NOT_ALLOWED, 'Permission denied!');
        }

        return $next($request);
    }
}
