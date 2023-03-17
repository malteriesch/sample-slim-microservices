<?php

namespace AppTests\Unit\Service;

use App\Queue\AbstractJob;
use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use App\Queue\QueueHandler;
use App\Service\ResourceService;
use AppTests\BaseTestCase;
use DI\Container;
use Predis\Client;

class QueueHandlerTest extends BaseTestCase
{

    protected MessageQueue $messageQueue;
    protected QueueHandler $queueHandler;
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->messageQueue = \Mockery::mock(MessageQueue::class);
        $this->container = \Mockery::mock(Container::class);

        $this->queueHandler = new QueueHandler($this->container, $this->messageQueue);
    }

    function test_executeNext()
    {
        $testJob = \Mockery::mock(AbstractJob::class);
        $this->container->shouldReceive('get')->once()->with('TestJob')->andReturns($testJob);
        $testJob->shouldReceive('setContainer')->with($this->container);
        $testJob->shouldReceive('setArguments')->with([
            'paramater' => 'value'
        ]);


        $testJob->shouldReceive('execute')->once();


        $this->messageQueue->shouldReceive('dequeue')->once()->andReturns(
            [
                'class' => 'TestJob',
                'parameters'=>[
                    'paramater' => 'value'
                ]
            ]
        );

        $this->assertNull($this->queueHandler->executeNext());
    }

    function test_executeNext_noJob()
    {

        $this->messageQueue->shouldReceive('dequeue')->once()->andReturns(
            null
        );

        $this->assertNull($this->queueHandler->executeNext());
    }

}

