<?php

namespace App\Factory;

use App\Environments;
use App\Middleware\RateLimiter;
use App\Middleware\StartSession;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Csrf\Guard;

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
