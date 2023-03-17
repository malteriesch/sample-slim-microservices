<?php

namespace AppTests\Unit\Controller;

use App\Controller\CreateResourceController;
use App\Service\RateLimitService;
use App\Service\ResourceService;
use AppTests\BaseTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateResourceControllerTest extends BaseTestCase
{

    protected CreateResourceController $createResourceController;
    protected RateLimitService         $rateLimitService;
    protected ResourceService          $resourceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rateLimitService = \Mockery::mock(RateLimitService::class);
        $this->resourceService  = \Mockery::mock(ResourceService::class);

        $this->createResourceController = new CreateResourceController($this->resourceService, $this->rateLimitService);
    }

    function test_resource_exists()
    {
        $request  = \Mockery::mock(ServerRequestInterface::class);
        $response = \Mockery::mock(ResponseInterface::class);

        $request->shouldReceive('getParsedBody')->once()->andReturns(['requestId'=>'abc']);
        $response->shouldReceive('getBody->write')->once()->with('{"request_id":"abc","created":true,"content":"123"}')->andReturns(['requestId'=>'abc']);
        $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturns($response);

        $this->rateLimitService->shouldReceive('tooManyRequests')->with('abc')->andReturns(false);
        $this->resourceService->shouldReceive('getResource')->with('abc')->andReturns('123');

        $this->assertEquals($response, $this->createResourceController->__invoke($request, $response, []));

    }
    function test_resource_notFound()
    {
        $request  = \Mockery::mock(ServerRequestInterface::class);
        $response = \Mockery::mock(ResponseInterface::class);

        $request->shouldReceive('getParsedBody')->once()->andReturns(['requestId'=>'abc']);
        $response->shouldReceive('getBody->write')->once()->with('{"request_id":"abc","created":false}')->andReturns(['requestId'=>'abc']);
        $response->shouldReceive('withHeader')->with('Content-Type', 'application/json')->andReturns($response);

        $this->rateLimitService->shouldReceive('tooManyRequests')->with('abc')->andReturns(false);
        $this->resourceService->shouldReceive('getResource')->with('abc')->andReturns(null);

        $this->assertEquals($response, $this->createResourceController->__invoke($request, $response, []));

    }
    function test_resource_rateLimit()
    {
        $request  = \Mockery::mock(ServerRequestInterface::class);
        $response = \Mockery::mock(ResponseInterface::class);

        $request->shouldReceive('getParsedBody')->once()->andReturns(['requestId'=>'abc']);
        $response->shouldReceive('withStatus')->once()->with(429)->andReturns($response);

        $this->rateLimitService->shouldReceive('tooManyRequests')->with('abc')->andReturns(true);

        $this->assertEquals($response, $this->createResourceController->__invoke($request, $response, []));

    }
    function test_resource_noResourceId()
    {
        $request  = \Mockery::mock(ServerRequestInterface::class);
        $response = \Mockery::mock(ResponseInterface::class);

        $request->shouldReceive('getParsedBody')->once()->andReturns(null);
        $response->shouldReceive('withStatus')->once()->with(400)->andReturns($response);

        $this->assertEquals($response, $this->createResourceController->__invoke($request, $response, []));

    }

}

