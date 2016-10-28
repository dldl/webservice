<?php

namespace dLdL\WebService;

/**
 * ParserInterface can be used to parse a raw response from an adapter.
 */
interface ParserInterface
{
    /**
     * This method can be used to parse a raw response from an adapter and convert
     * it to a business object.
     *
     * @param mixed $response Response to parse.
     * @return mixed
     */
    public function parse($response);
}
