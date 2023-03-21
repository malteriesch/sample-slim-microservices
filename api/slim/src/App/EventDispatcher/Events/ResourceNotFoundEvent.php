<?php
namespace App\EventDispatcher\Events;


use App\EventDispatcher\AbstractEvent;

class ResourceNotFoundEvent extends AbstractEvent
{

    public string $requestId;

    public function __construct(string $requestId)
    {
        $this->requestId = $requestId;
    }

    public function getRequestId(): string
    {
        return $this->requestId;
    }

}