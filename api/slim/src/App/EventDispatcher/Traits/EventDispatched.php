<?php
namespace App\EventDispatcher\Traits;


use App\EventDispatcher\EventDispatcherInterface;

trait EventDispatched
{
    protected ?EventDispatcherInterface $eventDispatcher = null;

    public function getEventDispatcher(): ?EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

}