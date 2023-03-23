<?php

namespace AppTests\Unit\Middleware;

use App\Middleware\LimitRates;
use App\Service\RateLimitService;
use AppTests\BaseTestCase;
use AppTests\Traits\MiddlewareTestingTrait;
use Mockery\MockInterface;

class LimitRateTest extends BaseTestCase
{

    use MiddlewareTestingTrait;

    private LimitRates                     $limitRates;
    private MockInterface|RateLimitService $rateLimitService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rateLimitService = \Mockery::mock(RateLimitService::class);
        $this->limitRates       = new LimitRates($this->rateLimitService);
        $this->setupMiddlewareTrait();
    }

    function test_withinLimits()
    {
        $this->request->shouldReceive('getParsedBody')->andReturns(['requestId' => 'abcdef']);
        $this->rateLimitService->shouldReceive('tooManyRequests')->with('abcdef')->andReturns(false);
        $this->expectMiddlewareSuccess($this->limitRates);
    }

    function test_outsideLimits()
    {
        $this->request->shouldReceive('getParsedBody')->andReturns(['requestId' => 'abcdef']);
        $this->rateLimitService->shouldReceive('tooManyRequests')->with('abcdef')->andReturns(true);
        $this->expectMiddlewareFailure(429, $this->limitRates);
    }

}

