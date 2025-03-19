<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

class CannotStoreUrlException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Could not store the shortened URL.');
    }
}
