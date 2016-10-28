<?php

namespace dLdL\WebService\Exception;

class WebServiceException extends \Exception
{
    protected $message = 'Execution error while interacting with the WebService.';

    public function __construct($message = null)
    {
        if (null !== $message) {
            $this->message = $message;
        }

        parent::__construct();
    }
}
