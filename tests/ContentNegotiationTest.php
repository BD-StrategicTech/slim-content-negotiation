<?php
/**
 * @author Matt Frost<mattf@budgetdumpster.com>
 * @package BudgetDumpster
 * @subpackage Tests
 * @subpackage Middleware
 * @copyright BudgetDumpster LLC, 2017
 */
namespace BudgetDumpster\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use BudgetDumpster\Middleware\ContentNegotiationHalJson;
use BudgetDumpster\Tests\Helpers\ContentNegotiationCallable;
use Slim\Http\Request;
use Slim\Http\Response;

class ContentNegotiationTest extends TestCase
{
    /**
     * @var BudgetDumpster\Tests\Unit\Helpers\ContentNegotiationCallable
     */
    private $callableMock;

    /**
     * @var Slim\Http\Request
     */
    private $request;

    /**
     * @var Slim\Http\Response
     */
    private $response;

    /**
     * Test Set up method
     */
    protected function setUp()
    {
        $this->callableMock = $this->getMockBuilder('\BudgetDumpster\Tests\Helpers\ContentNegotiationCallable')
            ->setMethods(['__invoke'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = $this->getMockBuilder('\Slim\Http\Request')
            ->setMethods(['getHeader'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->response = $this->getMockBuilder('\Slim\Http\Response')
            ->setMethods(['withStatus'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Tear Down method
     */
    protected function tearDown()
    {
        unset($this->callableMock);
        unset($this->request);
        unset($this->response);
    }

    /**
     * Test to ensure proper content type headers will hit the callable
     * and return a response
     *
     * @group middleware
     */
    public function testEnsureProperContentTypeAcceptHeadersCompleteSuccessfully()
    {
        $acceptResponse = ['application/json'];
        $contentTypeResponse = ['application/json'];

        $this->request->expects($this->at(0))
            ->method('getHeader')
            ->with('ACCEPT')
            ->will($this->returnValue($acceptResponse));

        $this->request->expects($this->at(1))
            ->method('getHeader')
            ->with('CONTENT_TYPE')
            ->will($this->returnValue($contentTypeResponse));

        $this->callableMock->expects($this->once())
            ->method('__invoke')
            ->with($this->request, $this->response)
            ->will($this->returnValue($this->response));

        $middleware = new ContentNegotiationHalJson();
        $response = $middleware->__invoke($this->request, $this->response, $this->callableMock);
        $this->assertInstanceOf('\Slim\Http\Response', $response);
    }

    /**
     * Test to ensure improper accepts header value returns a response
     * with a 406 request
     *
     * @group middleware
     */
    public function testEnsureImpromperAcceptsHeaderReturnsErrorCode()
    {
        $acceptResponse = ['text/html'];
    
        $this->request->expects($this->once())
            ->method('getHeader')
            ->with('ACCEPT')
            ->will($this->returnValue($acceptResponse));

        $this->response->expects($this->once())
            ->method('withStatus')
            ->with(406)
            ->will($this->returnValue($this->response));

        $this->callableMock->expects($this->never())
            ->method('__invoke');

        $middleware = new ContentNegotiationHalJson();
        $response = $middleware->__invoke($this->request, $this->response, $this->callableMock);
    }

    /**
     * Test to ensure a wrong content-type header returns an error
     *
     * @group middleware
     */
    public function testEnsureImpromperContentTypeReturnsErrorCode()
    {
        $acceptResponse = ['application/json'];
        $contentTypeResponse = ['text/html'];

        $this->request->expects($this->at(0))
            ->method('getHeader')
            ->with('ACCEPT')
            ->will($this->returnValue($acceptResponse));

        $this->request->expects($this->at(1))
            ->method('getHeader')
            ->with('CONTENT_TYPE')
            ->will($this->returnValue($contentTypeResponse));

        $this->callableMock->expects($this->never())
            ->method('__invoke');

        $this->response->expects($this->once())
            ->method('withStatus')
            ->with(415)
            ->will($this->returnValue($this->response));

        $middleware = new ContentNegotiationHalJson();
        $response = $middleware->__invoke($this->request, $this->response, $this->callableMock);
    }
}
