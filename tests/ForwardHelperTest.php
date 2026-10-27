<?php

declare(strict_types=1);

namespace tests;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use PHPUnit\Framework\TestCase;

class ForwardHelperTest extends TestCase
{
    public function testForwardDispatchesThroughMatcher(): void
    {
        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi.php');
        $application->bootstrap('testing');

        $response = forward(new ServerRequest('GET', '/source'), 'GET', '/');

        $this->assertSame('hello', (string) $response->getBody());
    }
}
