<?php

namespace dLdL\WebService\Tests;

use dLdL\WebService\AbstractConnector;
use dLdL\WebService\Exception\ConnectionException;
use dLdL\WebService\Exception\RequestException;
use Psr\Log\LoggerInterface;

class AbstractConnectorTest extends AbstractTestCase
{
    protected function getTestedClass()
    {
        $abstractConnector = $this->getMockForAbstractClass(AbstractConnector::class);

        return $abstractConnector;
    }

    public function testInitialization()
    {
        $abstractConnector = $this->getTestedClass();

        $this->assertFalse($abstractConnector->hasCache());
    }

    public function testSendRequestWithoutConnexion()
    {
        $abstractConnector = $this->getTestedClass();

        $abstractConnector->expects($this->once())
            ->method('isConnected')
            ->willReturn(false)
        ;

        $this->expectException(ConnectionException::class);

        $abstractConnector->sendRequest($this->getFakeGetRequest());
    }

    public function testUnsupportedRequestMethod()
    {
        $abstractConnector = $this->getTestedClass();

        $abstractConnector->expects($this->once())
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $abstractConnector->expects($this->once())
            ->method('isConnected')
            ->willReturn(true)
        ;

        $abstractConnector->expects($this->once())
            ->method('supportsMethod')
            ->with('POST')
            ->willReturn(false)
        ;

        $this->expectException(RequestException::class);

        $abstractConnector->sendRequest($this->getFakePostRequest());
    }

    public function testRequest()
    {
        $abstractConnector = $this->getTestedClass();
        $abstractConnector->setLogger($this->createMock(LoggerInterface::class));

        $abstractConnector->expects($this->exactly(3))
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $abstractConnector->expects($this->once())
            ->method('isConnected')
            ->willReturn(true)
        ;

        $abstractConnector->expects($this->once())
            ->method('supportsMethod')
            ->with('POST')
            ->willReturn(true)
        ;

        $abstractConnector->expects($this->once())
            ->method('handleRequest')
            ->with($this->getFakePostRequest())
            ->willReturn('response')
        ;

        $this->assertEquals('response', $abstractConnector->sendRequest($this->getFakePostRequest()));
    }
}
