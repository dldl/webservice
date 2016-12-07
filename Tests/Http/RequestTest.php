<?php

namespace dLdL\WebService\Tests\Http;

use dLdL\WebService\Tests\AbstractTestCase;

class RequestTest extends AbstractTestCase
{
    public function testGetRequest()
    {
        $request = $this->getFakeGetRequest();

        $this->assertEquals('GET', $request->getMethod());
        $this->assertSame(['test' => true], $request->getParameters());
        $this->assertEquals('/fake/get/url', $request->getUrl());
    }

    public function testPostRequest()
    {
        $request = $this->getFakePostRequest();

        $this->assertEquals('POST', $request->getMethod());
        $this->assertSame(['parameter' => 'value'], $request->getParameters());
        $this->assertEquals('/fake/post/url?hello=world', $request->getUrl());
    }
}
