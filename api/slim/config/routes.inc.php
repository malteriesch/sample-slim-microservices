<?php

use App\Controller\CreateResourceController;
use Slim\App;

return function (App $app) {
    $app->post("/api/create", CreateResourceController::class);
};