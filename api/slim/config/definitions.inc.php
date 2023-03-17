<?php

use Predis\Client;
use Psr\Container\ContainerInterface;
use Slim\App;

return [

    App::class             => fn(ContainerInterface $container) => (new \App\Factory\AppFactory())->createInstance($container),
    Client::class => fn(ContainerInterface $container) => new Client(
        [
            'scheme' => 'tcp',
            'host'   => getenv('REDIS_BACKEND'),
            'port'   => 6379,
        ]
    ),
];
