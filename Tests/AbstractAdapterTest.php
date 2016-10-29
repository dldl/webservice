<?php

namespace dLdL\WebService\Tests;

use dLdL\WebService\AbstractAdapter;
use dLdL\WebService\Exception\ConnectionException;
use dLdL\WebService\Exception\RequestException;
use Psr\Log\LoggerInterface;

class AbstractAdapterTest extends AbstractTestCase
{
    protected function getTestedClass()
    {
        $abstractAdapter = $this->getMockForAbstractClass(AbstractAdapter::class);

        return $abstractAdapter;
    }

    public function testInitialization()
    {
        $abstractAdapter = $this->getTestedClass();

        $this->assertFalse($abstractAdapter->hasCache());
    }

    public function testSendRequestWithoutConnexion()
    {
        $abstractAdapter = $this->getTestedClass();

        $abstractAdapter->expects($this->once())
            ->method('isConnected')
            ->willReturn(false)
        ;

        $this->expectException(ConnectionException::class);

        $abstractAdapter->sendRequest($this->getFakeGetRequest());
    }

    public function testUnsupportedRequestMethod()
    {
        $abstractAdapter = $this->getTestedClass();

        $abstractAdapter->expects($this->once())
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $abstractAdapter->expects($this->once())
            ->method('isConnected')
            ->willReturn(true)
        ;

        $abstractAdapter->expects($this->once())
            ->method('supportsMethod')
            ->with('POST')
            ->willReturn(false)
        ;

        $this->expectException(RequestException::class);

        $abstractAdapter->sendRequest($this->getFakePostRequest());
    }

    public function testRequest()
    {
        $abstractAdapter = $this->getTestedClass();
        $abstractAdapter->setLogger($this->createMock(LoggerInterface::class));

        $abstractAdapter->expects($this->exactly(3))
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $abstractAdapter->expects($this->once())
            ->method('isConnected')
            ->willReturn(true)
        ;

        $abstractAdapter->expects($this->once())
            ->method('supportsMethod')
            ->with('POST')
            ->willReturn(true)
        ;

        $abstractAdapter->expects($this->once())
            ->method('handleRequest')
            ->with($this->getFakePostRequest())
            ->willReturn('response')
        ;

        $this->assertEquals('response', $abstractAdapter->sendRequest($this->getFakePostRequest()));
    }
}
