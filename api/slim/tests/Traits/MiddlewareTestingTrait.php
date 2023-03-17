<?php

namespace AppTests\Traits;

use Mockery\MockInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Request;

trait MiddlewareTestingTrait
{

    protected MockInterface|RequestHandlerInterface $handler;
    protected MockInterface|Request                 $request;

    protected function setupMiddlewareTrait()
    {
        $this->handler = \Mockery::mock(RequestHandlerInterface::class);
        $this->request = \Mockery::mock(Request::class);
    }

    protected function expectMiddlewareSuccess(MiddlewareInterface $middleware)
    {
        $initialResponse = new \Slim\Psr7\Response(200);
        $this->handler->shouldReceive('handle')->with($this->request)->once()->andReturns($initialResponse);
        $response = $middleware->process($this->request, $this->handler);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($response, $initialResponse);
    }

    protected function expectMiddlewareFailure(int $expectedStatus, MiddlewareInterface $middleware)
    {
        $initialResponse = new \Slim\Psr7\Response(200);
        $this->handler->shouldNotReceive('handle');
        $response = $middleware->process($this->request, $this->handler);
        $this->assertEquals($expectedStatus, $response->getStatusCode());
        $this->assertNotEquals($response, $initialResponse);
        $this->assertEmpty((string)$response->getBody());
    }

}