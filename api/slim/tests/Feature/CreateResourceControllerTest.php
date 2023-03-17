<?php

namespace AppTests\Feature;

use App\Controller\CreateResourceController;
use App\Middleware\LimitRates;
use App\Middleware\ValidateRequest;
use App\Service\RateLimitService;
use App\Service\ResourceService;
use AppTests\BaseTestCase;
use AppTests\Traits\AppTestingTrait;
use Mockery\MockInterface;

class CreateResourceControllerTest extends BaseTestCase
{

    use AppTestingTrait;

    protected CreateResourceController       $createResourceController;
    protected MockInterface|RateLimitService $rateLimitService;
    protected MockInterface|ResourceService  $resourceService;
    protected array                          $mockedMiddleWareClassesCalled = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->rateLimitService = $this->mockContainerDependency(RateLimitService::class);
        $this->resourceService  = $this->mockContainerDependency(ResourceService::class);

        $this->expectMiddlewareToBeCalled(LimitRates::class);
        $this->expectMiddlewareToBeCalled(ValidateRequest::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->assertMiddlewareClassesCalledInOrder([
            ValidateRequest::class,
            LimitRates::class,
        ]);
    }

    function test_resource_exists()
    {
        $this->resourceService->shouldReceive('getResource')->with('abcdefg')->andReturns('123');

        $this->assertResponseValues([
            'request_id' => 'abcdefg',
            'created'    => true,
            'content'    => '123',
        ], $this->sendPostRequest('/api/create', ['requestId' => 'abcdefg']));
    }

    function test_resource_notFound()
    {
        $this->resourceService->shouldReceive('getResource')->with('abcdefg')->andReturns(null);

        $this->assertResponseValues([
            'request_id' => 'abcdefg',
            'created'    => false,
        ], $this->sendPostRequest('/api/create', ['requestId' => 'abcdefg']));
    }

}

