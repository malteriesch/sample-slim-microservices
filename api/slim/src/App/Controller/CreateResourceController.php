<?php

namespace App\Controller;

use App\Service\ResourceService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateResourceController extends AbstractController
{

    public function __construct(private ResourceService $resourceService)
    {
    }

    function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $requestId = $request->getParsedBody()["requestId"] ?? null;

        if(!$requestId){
            return $response->withStatus(400);
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