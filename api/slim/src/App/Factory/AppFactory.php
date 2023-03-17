<?php

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Slim\App;

final class AppFactory
{
    public function createInstance(ContainerInterface $container): App
    {
        $app = \Slim\Factory\AppFactory::createFromContainer($container);


        // Register middleware
        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware(true, true, true);


        // Register routes
        (require  'config/routes.inc.php')($app);


        return $app;
    }
}
