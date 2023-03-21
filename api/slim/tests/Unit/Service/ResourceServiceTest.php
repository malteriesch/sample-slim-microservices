<?php

namespace AppTests\Unit\Service;

use App\EventDispatcher\AbstractEvent;
use App\EventDispatcher\EventDispatcherInterface;
use App\EventDispatcher\Events\ResourceNotFoundEvent;
use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use App\Service\ResourceService;
use AppTests\BaseTestCase;
use Mockery\MockInterface;
use Predis\Client;

class ResourceServiceTest extends BaseTestCase
{

    protected ResourceService                        $resourceService;
    protected Client|MockInterface                   $redisClient;
    protected EventDispatcherInterface|MockInterface $eventDispatcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redisClient     = \Mockery::mock(Client::class);
        $this->eventDispatcher = \Mockery::mock(EventDispatcherInterface::class);

        $this->resourceService = (new ResourceService($this->redisClient))->setEventDispatcher($this->eventDispatcher);
    }

    function test_getResource()
    {
        $this->redisClient->shouldReceive('get')->with("resources:abc")->once()->andReturns('the resource');
        $this->assertEquals('the resource', $this->resourceService->getResource('abc'));
    }

    function test_getResource_doesNotExist()
    {
        $this->redisClient->shouldReceive('get')->with("resources:abc")->once()->andReturns(null);
        $this->eventDispatcher->shouldReceive('dispatchEvent')->withArgs(function(ResourceNotFoundEvent $event){
            $this->assertEquals('abc', $event->getRequestId());
            return true;
        });
        $this->assertEquals(null, $this->resourceService->getResource('abc'));
    }
}

