<?php

namespace App\Queue;

use DI\Container;

class QueueHandler
{

    public function __construct(private Container $container, private MessageQueue $messageQueue)
    {

    }

    public function executeNext()
    {
        if($config = $this->messageQueue->dequeue()){
            $job = $this->container->get($config['class'])->setArguments($config['parameters'])->setContainer($this->container);
            $job->execute();
        }
    }
}