<?php

use App\Controller\CreateResourceController;
use App\Middleware\LimitRates;
use App\Middleware\ValidateRequest;
use Slim\App;

return function (App $app) {
    $app->post("/api/create", CreateResourceController::class)
        ->add(LimitRates::class)
        ->add(ValidateRequest::class)
    ;
};