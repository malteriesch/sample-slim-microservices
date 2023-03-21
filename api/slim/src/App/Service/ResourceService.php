<?php

namespace App\Service;

use App\EventDispatcher\Events\ResourceNotFoundEvent;
use App\EventDispatcher\Traits\EventDispatched;
use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use Predis\Client;

class ResourceService
{

    use EventDispatched;

    public function __construct(private Client $redisClient)
    {
    }

    public function getResource(string $resourceId): ?string
    {
        $content = $this->redisClient->get("resources:$resourceId");

        if ($content) {
            return $content;
        }
        $this->eventDispatcher->dispatchEvent(new ResourceNotFoundEvent($resourceId));
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