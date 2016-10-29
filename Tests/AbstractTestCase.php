<?php

namespace dLdL\WebService\Tests;

use dLdL\WebService\Http\Request;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getFakePostRequest()
    {
        return new Request('/fake/post/url?hello=world', 'POST', ['parameter' => 'value']);
    }

    protected function getFakeGetRequest()
    {
        return new Request('/fake/get/url', 'GET', ['test' => true]);
    }

    abstract protected function getTestedClass();
}
