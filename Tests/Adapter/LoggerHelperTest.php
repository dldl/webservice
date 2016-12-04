<?php

namespace dLdL\WebService\Tests\Adapter;

use dLdL\WebService\Adapter\LoggerHelper;
use dLdL\WebService\ConnectorInterface;
use dLdL\WebService\Http\Request;
use dLdL\WebService\Tests\AbstractTestCase;
use Psr\Log\LoggerInterface;

class LoggerHelperTest extends AbstractTestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|Request */
    private $request;
    /** @var \PHPUnit_Framework_MockObject_MockObject|ConnectorInterface */
    private $connector;
    /** @var \PHPUnit_Framework_MockObject_MockObject|LoggerInterface */
    private $logger;

    public function setUp()
    {
        $this->connector = $this->getMockBuilder(ConnectorInterface::class)
            ->setMockClassName('RandomConnector')
            ->getMock()
        ;

        $this->request = $this->createMock(Request::class);
        $this->request->expects($this->once())
            ->method('getUrl')
            ->willReturn('/test/url')
        ;

        $this->logger = $this->createMock(LoggerInterface::class);
    }

    protected function getTestedClass($logger)
    {
        return new LoggerHelper($logger);
    }

    public function testRequest()
    {
        $this->request->expects($this->once())
            ->method('getParameters')
            ->willReturn(['number' => 42])
        ;

        $this->logger->expects($this->once())
            ->method('info')
            ->with('Sending request to http://example.com/test/url using RandomConnector.', ['number' => 42])
        ;

        $this->connector->expects($this->once())
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $this->getTestedClass($this->logger)->request($this->connector, $this->request);
    }

    public function testResponse()
    {
        $this->logger->expects($this->once())
            ->method('debug')
            ->with('Response trace for http://example.com/test/url.', [['response' => 'ok']])
        ;

        $this->connector->expects($this->once())
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $this->getTestedClass($this->logger)->response($this->connector, ['response' => 'ok'], $this->request);
    }

    public function testCacheGet()
    {
        $this->request->expects($this->once())
            ->method('getParameters')
            ->willReturn(['number' => 42])
        ;

        $this->logger->expects($this->once())
            ->method('info')
            ->with('Retrieving data for http://example.com/test/url from cache RandomCache.', ['number' => 42])
        ;

        $this->getTestedClass($this->logger)->cacheGet('http://example.com', $this->request, 'RandomCache');
    }

    public function testCacheAdd()
    {
        $this->request->expects($this->once())
            ->method('getParameters')
            ->willReturn(['number' => 42])
        ;

        $this->logger->expects($this->once())
            ->method('info')
            ->with('Adding response for http://example.com/test/url to cache RandomCache (will expire in 3600 seconds).', ['number' => 42])
        ;

        $this->getTestedClass($this->logger)->cacheAdd('http://example.com', $this->request, 'RandomCache', 3600);
    }

    public function testConnectionFailure()
    {
        $this->request->expects($this->once())
            ->method('getParameters')
            ->willReturn(['number' => 42])
        ;

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Failed to connect to http://example.com/test/url using RandomConnector.', ['number' => 42])
        ;

        $this->connector->expects($this->once())
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $this->getTestedClass($this->logger)->connectionFailure($this->connector, $this->request);
    }

    public function testRequestFailure()
    {
        $this->request->expects($this->once())
            ->method('getParameters')
            ->willReturn(['number' => 42])
        ;

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Failed to send request to http://example.com/test/url using RandomConnector. Exception message : No Internet connection.', ['number' => 42])
        ;

        $this->connector->expects($this->once())
            ->method('getHost')
            ->willReturn('http://example.com')
        ;

        $this->getTestedClass($this->logger)->requestFailure($this->connector, $this->request, 'No Internet connection.');
    }
}
