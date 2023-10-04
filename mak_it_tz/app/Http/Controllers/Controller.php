<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Admin panel",
 *     version="1.0.0",
 *     description="Admin panel for management"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     in="header",
 *     securityScheme="bearer",
 *     scheme="bearer"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
