<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserDoesNotExistsException extends \Exception
{
    public function __construct() {
        parent::__construct("Пользователя не существуетю", ResponseAlias::HTTP_NOT_FOUND);
    }
}
