<?php

namespace App\Exceptions;

use App\Models\UserProtocol;
use Exception;

class SameProtocolActiveException extends Exception
{
    protected UserProtocol $activeProtocol;

    public function __construct(UserProtocol $activeProtocol)
    {
        parent::__construct('You still have an active same protocol.', 422);
        $this->activeProtocol = $activeProtocol;
    }

    public function getActiveProtocol(): UserProtocol
    {
        return $this->activeProtocol;
    }
}
