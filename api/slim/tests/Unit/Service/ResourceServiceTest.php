<?php

namespace AppTests\Unit\Service;

use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use App\Service\ResourceService;
use AppTests\BaseTestCase;
use Mockery\MockInterface;
use Predis\Client;

class ResourceServiceTest extends BaseTestCase
{

    protected ResourceService            $resourceService;
    protected MockInterface|Client       $redisClient;
    protected MockInterface|MessageQueue $messageQueue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redisClient  = \Mockery::mock(Client::class);
        $this->messageQueue = \Mockery::mock(MessageQueue::class);

        $this->resourceService = new ResourceService($this->redisClient, $this->messageQueue);
    }

    function test_getResource()
    {
        $this->redisClient->expects()->get("resources:abc")->once()->andReturns('the resource');
        $this->assertEquals('the resource', $this->resourceService->getResource('abc'));
    }

    function test_getResource_doesNotExist()
    {
        $this->redisClient->expects()->get("resources:abc")->once()->andReturns(null);
        $this->messageQueue->expects()->enqueue(CreateResourceJob::class, ['requestId' => 'abc'])->once();
        $this->assertEquals(null, $this->resourceService->getResource('abc'));
    }
}

