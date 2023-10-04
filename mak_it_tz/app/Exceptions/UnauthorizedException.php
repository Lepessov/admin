<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UnauthorizedException extends Exception
{
    public function __construct() {
        parent::__construct("Вы не авторизованы!", ResponseAlias::HTTP_NOT_FOUND);
    }
}
