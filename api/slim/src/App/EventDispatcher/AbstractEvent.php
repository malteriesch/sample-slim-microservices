<?php

namespace App\EventDispatcher;


abstract class AbstractEvent extends \Symfony\Contracts\EventDispatcher\Event
{

    function getEventName()
    {
        return get_class($this);
    }
}