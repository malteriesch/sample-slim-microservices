<?php

namespace AppTests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        \Mockery::close();
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}