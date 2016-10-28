<?php

namespace dLdL\WebService\Exception;

class RequestException extends WebServiceException
{
    protected $message = 'Request cannot be handled by the adapter.';

    public function __construct($message = null)
    {
        if (null !== $message) {
            $this->message = $message;
        }

        parent::__construct();
    }
}
