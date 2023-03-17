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

    public function getResource(string $resourceId): ?string
    {
        $content = $this->redisClient->get("resources:$resourceId");

        if ($content) {
            return $content;
        }

        $this->messageQueue->enqueue(CreateResourceJob::class, ['resourceId' => $resourceId]);
        return null;
    }

    public function create($resourceId): void
    {
        //quick way of creating an arbitrary delay to simulate expensive operation
        $numberOfSeconds = ((crc32($resourceId) % 5) * 5) + 1;
        sleep($numberOfSeconds);
        $this->redisClient->set("resources:$resourceId", md5($resourceId));
    }

}