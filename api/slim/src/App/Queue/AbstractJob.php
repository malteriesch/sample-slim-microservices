<?php

namespace App\Queue;

abstract class AbstractJob
{
    protected array $arguments = [];
    protected \Di\Container $container;

    public function setArguments(array $arguments): static
    {
        $this->arguments = $arguments;
        return $this;
    }

    public function setContainer(\Di\Container $container): static
    {
        $this->container = $container;
        return $this;
    }

    abstract public function execute();
}