<?php

use App\EventDispatcher\ContainerAwareEventDispatcher;
use App\EventDispatcher\Events\ResourceNotFoundEvent;
use App\EventDispatcher\Handlers\RequestResourceCreation;
use App\Queue\MessageQueue;
use App\Service\ResourceService;
use Predis\Client;
use Psr\Container\ContainerInterface;
use Slim\App;
use Symfony\Component\EventDispatcher\EventDispatcher;

return [

    App::class             => fn(ContainerInterface $container) => (new \App\Factory\AppFactory())->createInstance($container),
    Client::class          => fn(ContainerInterface $container) => new Client(
        [
            'scheme' => 'tcp',
            'host'   => getenv('REDIS_BACKEND'),
            'port'   => 6379,
        ]
    ),
    ResourceService::class =>
        fn(ContainerInterface $container) =>
        (new ResourceService($container->get(Client::class)))
            ->setEventDispatcher($container->get(EventDispatcher::class)),

    EventDispatcher::class => function (ContainerInterface $container) {
        $eventDispatcher = new ContainerAwareEventDispatcher($container);

        $eventDispatcher->configure(
            [
                ResourceNotFoundEvent::class => [
                    RequestResourceCreation::class,
                ],
            ]
        );
        return $eventDispatcher;
    },
];
