<?php

namespace AppTests\Unit\Service;

use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use App\Service\RateLimitService;
use App\Service\ResourceService;
use AppTests\BaseTestCase;
use Predis\Client;

class RateLimitServiceTest extends BaseTestCase
{

    protected RateLimitService $rateLimitService;
    protected Client          $redisClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redisClient  = \Mockery::mock(Client::class);

        $this->rateLimitService = new RateLimitService($this->redisClient);
    }

    function test_tooManyRequests_false()
    {
        $this->redisClient->shouldReceive('get')->with("rate-limit:abc")->once()->andReturns(null);
        $this->redisClient->shouldReceive('set')->with("rate-limit:abc", 'set')->once();
        $this->redisClient->shouldReceive('pexpire')->with("rate-limit:abc", 500)->once();
        $this->assertFalse($this->rateLimitService->tooManyRequests('abc'));

    }
    function test_tooManyRequests_true()
    {
        $this->redisClient->shouldReceive('get')->with("rate-limit:abc")->once()->andReturns('set');
        $this->assertTrue($this->rateLimitService->tooManyRequests('abc'));

    }

}

