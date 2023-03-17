<?php

declare(strict_types=1);

chdir(dirname(__DIR__));

use App\Factory\ContainerFactory;
use Slim\App;

require_once 'vendor/autoload.php';

$container = (new ContainerFactory())->createInstance();
$app = $container->get(App::class);
$app->run();