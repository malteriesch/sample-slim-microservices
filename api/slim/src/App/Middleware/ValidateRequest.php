<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class ValidateRequest implements MiddlewareInterface
{

    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestId = $request->getParsedBody()["requestId"] ?? null;
        if(!$requestId){
            return new Response(400);
        }
        return $handler->handle($request);
    }
}