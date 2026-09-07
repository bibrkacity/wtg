<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class OfferLockedException extends ApiException
{
    public function __construct()
    {
        parent::__construct('Offer is not available anymore', ResponseAlias::HTTP_CONFLICT);
    }
}
