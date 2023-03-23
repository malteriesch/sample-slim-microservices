<?php

namespace App\Service;

use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use Predis\Client;

class ResourceService
{

    public function __construct(private Client $redisClient, private MessageQueue $messageQueue)
    {
    }

    public function getResource(string $requestId): ?string
    {
        $content = $this->redisClient->get("resources:$requestId");

        if ($content) {
            return $content;
        }

        $this->messageQueue->enqueue(CreateResourceJob::class, ['requestId' => $requestId]);
        return null;
    }

    public function create($requestId): void
    {
        //quick way of creating an arbitrary delay to simulate expensive operation
        $numberOfSeconds = ((crc32($requestId) % 6)) + 5; // between 5 and 10 inclusive
        sleep($numberOfSeconds);
        $this->redisClient->set("resources:$requestId", md5($requestId));
    }

}