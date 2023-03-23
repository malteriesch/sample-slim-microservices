<?php

namespace App\Service;

use Predis\Client;

class RateLimitService
{

    public function __construct(private Client $redisClient, private int $blockTimeInMilliseconds = 500)
    {
    }

    public function tooManyRequests(string $requestId): bool
    {
        if (null == $this->redisClient->get("rate-limit:$requestId")) {
            $this->redisClient->set("rate-limit:$requestId", 'set');
            $this->redisClient->pexpire("rate-limit:$requestId", $this->blockTimeInMilliseconds);
            return false;
        }
        return true;
    }

}