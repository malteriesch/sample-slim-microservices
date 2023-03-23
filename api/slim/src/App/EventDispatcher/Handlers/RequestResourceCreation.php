<?php
namespace App\EventDispatcher\Handlers;

use App\EventDispatcher\Events\ResourceNotFoundEvent;
use App\Queue\CreateResourceJob;
use App\Queue\MessageQueue;

class RequestResourceCreation
{

    function __construct(private MessageQueue $messageQueue)
    {
    }

    function __invoke(ResourceNotFoundEvent $event)
    {
        $this->messageQueue->enqueue(CreateResourceJob::class, ['requestId' => $event->getRequestId()]);
    }
}