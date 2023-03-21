<?php

namespace App\EventDispatcher;


use Di\Container;
use Psr\EventDispatcher\StoppableEventInterface;

class ContainerAwareEventDispatcher extends \Symfony\Component\EventDispatcher\EventDispatcher implements EventDispatcherInterface
{

    protected Container $container;

    public function __construct(Container $container = null)
    {
        parent::__construct();
        $this->container = $container;
    }

    public function dispatchEvent(AbstractEvent $event)
    {
        $this->dispatch($event, $event->getEventName());
    }

    protected function callListeners(iterable $listeners, string $eventName, object $event)
    {
        $stoppable = $event instanceof StoppableEventInterface;

        foreach ($listeners as $listener) {
            if ($stoppable && $event->isPropagationStopped()) {
                break;
            }
            if(is_array($listener)){
                $listener = $this->container->get($listener[0]);
            }
            $listener($event, $eventName, $this);
        }
    }

    function configure(array $configuration){
        foreach($configuration as $eventName => $listeners){
            $this->addListeners($eventName, $listeners);
        }
    }

    function addListeners($eventName, array $listeners)
    {
        foreach($listeners as $listener){
            $this->addListener($eventName, [$listener]);
        }
    }
}