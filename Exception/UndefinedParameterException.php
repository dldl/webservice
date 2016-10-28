<?php

namespace dLdL\WebService\Exception;

class UndefinedParameterException extends \Exception
{
    protected $message = 'Undefined parameter for this ParameterBag.';

    public function __construct($message = null)
    {
        if (null !== $message) {
            $this->message = $message;
        }

        parent::__construct();
    }
}
