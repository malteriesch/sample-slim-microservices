<?php

namespace App\Controller;

use App\Service\RateLimitService;
use App\Service\ResourceService;
use AppTests\Unit\Service\RateLimitServiceTest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateResourceController extends AbstractController
{

    public function __construct(private ResourceService $resourceService, private RateLimitService $rateLimitService)
    {
    }

    function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $requestId = $request->getParsedBody()["requestId"] ?? null;

        if(!$requestId){
            return $response->withStatus(400);
        }

        //@todo refactor: move to middleware
        if($this->rateLimitService->tooManyRequests($requestId)){
            return $response->withStatus(429);
        }

        $content = $this->resourceService->getResource($requestId);
        $toRespond = [
            "request_id" => $requestId,
            'created' => null !== $content
        ];

        if(null !== $content){
            $toRespond['content'] = $content;
        }
        return $this->withJson($response,$toRespond);
    }
}