<?php

namespace AppTests\Traits;

use App\Factory\ContainerFactory;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\App;
use Slim\Psr7\Factory\UriFactory;
use Slim\Psr7\NonBufferedBody;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

trait AppTestingTrait
{

    protected ?Container $container = null;

    public function getContainer(): Container
    {
        if (null === $this->container) {
            $this->container = (new ContainerFactory())->createInstance();
        }
        return $this->container;
    }

    protected function createRequest($url = '/foo.php', $method = "GET", $headers = [], ?array $serverParams = null): Request
    {
        $headers = new \Slim\Psr7\Headers(
            array_merge($headers, [
                'Accept' => 'application/json',
            ])
        );

        $uri = (new UriFactory())->createUri($url);

        $cookies = [];

        return (new \Slim\Psr7\Request(
            method: $method,
            uri: $uri,
            headers: $headers,
            cookies: $cookies,
            serverParams: $serverParams ?? ['REMOTE_ADDR' => '127.0.0.1'],
            body: new NonBufferedBody()
        ));
    }

    protected function createResponse()
    {
        $headers  = new \Slim\Psr7\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
        $response = new Response(200, $headers);
        return $response;
    }

    protected function sendPostRequest($url, $post = []): Response
    {
        return $this->runApp($this->createRequest($url, 'POST')->withParsedBody($post));
    }

    protected function runApp(Request $request): ResponseInterface
    {
        $response = $this->createResponse();
        $this->getContainer()->set(\Slim\Psr7\Request::class, $request);
        return $this->getContainer()->get(App::class)->handle($request);
    }

    protected function mockContainerDependency(string $fullClassName, string $diName = null)
    {
        $mock = \Mockery::mock($fullClassName);
        $this->getContainer()->set($diName ?: $fullClassName, fn() => $mock);
        return $mock;
    }

    function expectMiddlewareToBeCalled(string $className)
    {
        $middleware = $this->mockContainerDependency($className);

        $logger = function ($className) {
            $this->mockedMiddleWareClassesCalled[] = $className;
        };

        $middleware->shouldReceive('process')->once()->andReturnUsing(function (Request $request, RequestHandlerInterface $handler) use ($className, $logger) {
            $logger($className);
            return $handler->handle($request);
        });
    }

    function assertMiddlewareClassesCalledInOrder(array $classNames)
    {
        $this->assertSame($classNames, $this->mockedMiddleWareClassesCalled);
    }

    function assertResponseValues(array $expectedValues, Response $response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedValues, json_decode($response->getBody(), true));
    }
}