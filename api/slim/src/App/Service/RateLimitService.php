<?php

namespace App\Service;

use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;
use Predis\Client;

class RateLimitService
{

    public function __construct(private Client $redisClient, private int $blockTimeInMilliseconds = 500)
    {
    }

    public function tooManyRequests(string $resourceId): bool
    {
        if (null == $this->redisClient->get("rate-limit:$resourceId")) {
            $this->redisClient->set("rate-limit:$resourceId", 'set');
            $this->redisClient->pexpire("rate-limit:$resourceId", $this->blockTimeInMilliseconds);
            return false;
        }
        return true;
    }

}