<?php

namespace App\Middleware;

use App\Service\RateLimitService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

class LimitRates implements MiddlewareInterface
{

    public function __construct(private RateLimitService $rateLimitService)
    {
    }

    function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestId = $request->getParsedBody()["requestId"] ?? null;
        if($this->rateLimitService->tooManyRequests($requestId)){
            return new Response(429);
        }
        return $handler->handle($request);
    }
}