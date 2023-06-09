<?php

namespace AppTests\Unit\Service;

use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use AppTests\BaseTestCase;
use Mockery\MockInterface;
use Predis\Client;

class MessageQueueTest extends BaseTestCase
{

    protected MessageQueue         $messageQueue;
    protected MockInterface|Client $redisClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redisClient = \Mockery::mock(Client::class);

        $this->messageQueue = new MessageQueue($this->redisClient);
    }

    private function createExampleJson(): string|false
    {
        return json_encode($this->createExampleJob());
    }

    private function createExampleJob(): array
    {
        return ['class' => CreateResourceJob::class, 'parameters' => ['requestId' => 123]];
    }

    function test_enqueue()
    {
        $this->redisClient->shouldReceive('sadd')->with('queues', ['default'])->once();
        $this->redisClient->shouldReceive('rpush')->with('queue:default', $this->createExampleJson())->once();

        $this->assertNull($this->messageQueue->enqueue(CreateResourceJob::class, ['requestId' => 123]));
    }

    function test_dequeue()
    {
        $this->redisClient->shouldReceive('lpop')->with('queue:default')->once()->andReturns($this->createExampleJson());

        $this->assertEquals($this->createExampleJob(), $this->messageQueue->dequeue());
    }

    function test_dequeue_noJob()
    {
        $this->redisClient->shouldReceive('lpop')->with('queue:default')->once()->andReturns(null);

        $this->assertNull($this->messageQueue->dequeue());
    }
}

