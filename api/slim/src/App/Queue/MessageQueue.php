<?php

namespace App\Queue;

use Predis\Client;

class MessageQueue
{

    private Client $redis;

    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    function enqueue(string $jobClass, array $parameters = [])
    {
        $this->redis->sadd('queues', ['default']);
        $this->redis->rpush('queue:default', json_encode(['class'=>$jobClass, 'parameters' => $parameters]));
    }

    function dequeue() : ?array
    {
        $config = $this->redis->lpop('queue:default');

        if(null === $config){
            return null;
        }
       return json_decode($config, true);
    }
}