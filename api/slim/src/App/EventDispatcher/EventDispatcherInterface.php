<?php

namespace App\EventDispatcher;


interface EventDispatcherInterface extends \Symfony\Component\EventDispatcher\EventDispatcherInterface
{
    public function dispatchEvent(AbstractEvent $event);
}