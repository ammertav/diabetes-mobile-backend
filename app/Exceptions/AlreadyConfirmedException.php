<?php

namespace App\Exceptions;

use App\Models\FastingLog;
use Exception;

class AlreadyConfirmedException extends Exception
{
    protected FastingLog $log;

    public function __construct(FastingLog $log)
    {
        parent::__construct('Already confirmed', 409);
        $this->log = $log;
    }

    public function getLog(): FastingLog
    {
        return $this->log;
    }
}
