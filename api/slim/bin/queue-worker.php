<?php

use App\Factory\ContainerFactory;
use App\Queue\QueueHandler;

include_once __DIR__ . '/../vendor/autoload.php';


$container = (new ContainerFactory())->createInstance();

/**
 * @var QueueHandler $queue
 */
$queueHandler = $container->get(QueueHandler::class);
while(true)
{
    $queueHandler->executeNext();
    sleep(1);
}
