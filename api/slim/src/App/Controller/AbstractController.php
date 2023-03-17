<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;

class AbstractController
{

    public function withJson(ResponseInterface $response, array $toEncode): ResponseInterface
    {
        $response->getBody()->write(json_encode($toEncode));
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}