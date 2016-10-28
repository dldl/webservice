<?php

namespace dLdL\WebService\Adapter;

use dLdL\WebService\Exception\UndefinedParameterException;

/**
 * ParameterBag that can be used to define parameters (used by adapters and cache system).
 */
interface ParameterBagInterface
{
    /**
     * Redefine the whole bag from the parameters.
     *
     * @param array $parameters
     */
    public function set(array $parameters);

    /**
     * Get a specific value.
     *
     * @param string $name         Key of the searched value
     * @param string $defaultValue Returned value if key is not found
     *
     * @return mixed The searched value of the default one if not found
     *
     * @throws UndefinedParameterException If value is not found and default value is null
     */
    public function get($name, $defaultValue = null);

    /**
     * Add a value to the bag.
     *
     * @param string $name Key of the added value
     * @param mixed $value Value added to the bag
     */
    public function add($name, $value);

    /**
     * Remove a specific value.
     *
     * @param string $name Key of the value to be deleted
     */
    public function remove($name);

    /**
     * Check if the bag contains a specific value.
     *
     * @param string $name Key for the searched value
     *
     * @return true if the value is found, false otherwise
     */
    public function has($name);
}
