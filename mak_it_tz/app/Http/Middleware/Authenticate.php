<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Authenticate extends Middleware
{
    use ApiResponse;

    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        return $this->errorResponse(null, ResponseAlias::HTTP_UNAUTHORIZED, 'Unauthorized');
    }
}
