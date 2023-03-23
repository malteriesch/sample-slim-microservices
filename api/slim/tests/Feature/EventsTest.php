<?php

namespace AppTests\Feature;


use App\EventDispatcher\Events\ResourceNotFoundEvent;
use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use App\Service\ResourceService;
use AppTests\BaseTestCase;
use AppTests\Traits\AppTestingTrait;
use Mockery\MockInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventsTest extends BaseTestCase
{
    use AppTestingTrait;

    private MessageQueue|MockInterface $messageQueue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageQueue = $this->mockContainerDependency(MessageQueue::class);
    }



    function test_resourceServiceReceivedEventDispatcher()
    {
        $eventDispatcher = $this->container->get(EventDispatcher::class);
        $this->assertSame($this->container->get(ResourceService::class)->getEventDispatcher(), $eventDispatcher);
    }

    function test_ResourceNotFoundEvent()
    {
        $event = new ResourceNotFoundEvent('abcdefgh');

        $this->messageQueue->shouldReceive('enqueue')->with(CreateResourceJob::class, ['requestId' => $event->getRequestId()]);

        $this->assertNull($this->container->get(EventDispatcher::class)->dispatchEvent($event));
    }

}