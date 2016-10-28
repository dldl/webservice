<?php

namespace dLdL\WebService\Adapter;

use dLdL\WebService\Exception\UndefinedParameterException;

class ParameterBag implements ParameterBagInterface
{
    private $options;

    public function __construct()
    {
        $this->options = [];
    }

    public function set(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    public function get($name, $defaultValue = null)
    {
        if (!$this->has($name)) {
            if ($defaultValue === null) {
                throw new UndefinedParameterException('Option '.$name.' must be defined.');
            } else {
                return $defaultValue;
            }
        }

        return $this->options[$name];
    }

    public function add($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function remove($name)
    {
        unset($this->options[$name]);
    }

    public function has($name)
    {
        return array_key_exists($name, $this->options);
    }
}
