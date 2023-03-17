<?php

namespace App\Factory;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

final class ContainerFactory
{
    public function createInstance(): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions('config/definitions.inc.php');
        return $containerBuilder->build();
    }
}
