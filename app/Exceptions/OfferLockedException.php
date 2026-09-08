<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OfferLockedException extends ApiException
{
    public function __construct()
    {
        parent::__construct('Wait for another user to release the lock', ResponseAlias::HTTP_LOCKED);
    }
}
