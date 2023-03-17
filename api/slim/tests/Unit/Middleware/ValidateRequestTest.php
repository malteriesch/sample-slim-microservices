<?php

namespace AppTests\Unit\Middleware;

use App\Middleware\ValidateRequest;
use AppTests\BaseTestCase;
use AppTests\Traits\MiddlewareTestingTrait;

class ValidateRequestTest extends BaseTestCase
{

    use MiddlewareTestingTrait;

    private ValidateRequest $validateRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validateRequest = new ValidateRequest();
        $this->setupMiddlewareTrait();
    }

    function test_ok()
    {
        $this->request->shouldReceive('getParsedBody')->andReturns(['requestId' => 'abcdef']);
        $this->expectMiddlewareSuccess($this->validateRequest);
    }

    function test_missingParameter()
    {
        $this->request->shouldReceive('getParsedBody')->andReturns([]);
        $this->expectMiddlewareFailure(400, $this->validateRequest);
    }

}

