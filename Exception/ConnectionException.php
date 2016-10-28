<?php

namespace dLdL\WebService\Exception;

class ConnectionException extends WebServiceException
{
    protected $message = 'Connection must be established before receiving requests.';

    public function __construct($message = null)
    {
        if (null !== $message) {
            $this->message = $message;
        }

        parent::__construct();
    }
}
